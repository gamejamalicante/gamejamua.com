<?php

namespace GJA\GameJam\CompoBundle\Controller;

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Event\TeamEvent;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;
use GJA\GameJam\CompoBundle\Form\Type\TeamInvitationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamRequestType;
use GJA\GameJam\CompoBundle\Form\Type\TeamType;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/compos/{compo}/team")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_team")
     * @Template()
     */
    public function indexAction(Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        $teams = $this->getRepository("GameJamCompoBundle:Team")->findBy(['compo' => $compo]);

        $templateVars = [
            'compo' => $compo,
            'team' => null,
            'user' => $user,
            'invite_form' => null,
            'creation_form' => null,
            'request_form' => null,
            'is_leader' => false,
            'teams' => $teams,
            'open_formation' => $compo->isTeamFormationOpen(),
            'user_application' => $compo->getApplicationForUser($user)
        ];

        if($team = $user->getTeamForCompo($compo))
        {
            $templateVars['team'] = $team;
            $templateVars['is_leader'] = $team->getLeader() === $user;

            if($team->getLeader() === $user)
            {
                $teamForm = $this->createForm(new TeamInvitationType($this->getEntityManager()));

                $templateVars['invite_form'] = $teamForm->createView();
            }
        }
        else
        {
            $teamCreateForm = $this->createForm(new TeamType());
            $teamForm = $this->createForm(new TeamRequestType($compo));

            $templateVars['creation_form'] = $teamCreateForm->createView();
            $templateVars['request_form'] = $teamForm->createView();
        }

        return $templateVars;
    }

    /**
     * @Route("/enviar-peticion", name="gamejam_compo_team_send_request")
     * @Method({"POST"})
     */
    public function submitRequest(Request $request, Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        $teamForm = $this->createForm(new TeamRequestType($compo));

        if($user->getTeamForCompo($compo))
        {
            $this->addSuccessMessage("<strong>Error</strong>: ¡Ya tienes equipo para esta GameJam! Si quieres cambiar, salte primero y vuelve a enviar la petición");

            return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
        }

        $teamForm->handleRequest($request);

        if($teamForm->isValid())
        {
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = $teamForm->getData();
            $leader = $teamInvitation->getTeam()->getLeader();

            if($user === $leader)
            {
                $this->addSuccessMessage("<strong>Error</strong>: ¡No puedes hacer peticiones a tu propio equipo!");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            if($user->getApplicationTo($compo)->getModality() !== $leader->getApplicationTo($compo)->getModality())
            {
                $this->addSuccessMessage("<strong>Error</strong>: No podéis formar parte del mismo equipo ya que no estáis bajo la misma modalidad");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            $teamInvitation->setTarget($leader);
            $teamInvitation->setSender($this->getUser());
            $teamInvitation->setCompo($compo);
            $teamInvitation->setType(TeamInvitation::TYPE_REQUEST);

            try
            {
                $this->persistAndFlush($teamInvitation);
            }
            catch(UniqueConstraintViolationException $ex)
            {
                $this->addSuccessMessage("<strong>Error</strong>: ¡Ya has enviado esa petición, you silly goose!");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST, new TeamInvitationEvent($teamInvitation));

            $this->addSuccessMessage("¡Hemos enviado tu petición correctamente a <strong>" .$teamInvitation->getTeam(). "</strong> correctamente!");
        }
        else
        {
            $this->addSuccessMessage("<strong>Error</strong>: Ocurrió un error enviando la invitación. Por favor, vuelve a intentarlo");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/enviar-invitacion", name="gamejam_compo_team_send_invitation")
     * @Method({"POST"})
     */
    public function submitInvitation(Request $request, Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        /** @var User $leader */
        $leader = $user;
        $team = $leader->getTeamForCompo($compo);

        if($leader !== $team->getLeader())
            throw new NotFoundHttpException;

        $teamInviteForm = $this->createForm(new TeamInvitationType($this->getEntityManager()));

        $teamInviteForm->handleRequest($request);

        if($teamInviteForm->isValid())
        {
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = $teamInviteForm->getData();

            if(is_null($target = $teamInvitation->getTarget()))
            {
                $this->addSuccessMessage("<strong>Error</strong>: El usuario no existe");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            if($target === $leader)
            {
                $this->addSuccessMessage("<strong>Error</strong>: ¡No puedes invitarte a tí mismo!");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            if($targetApplication = $target->getApplicationTo($compo))
            {
                if($targetApplication->getModality() !== $leader->getApplicationTo($compo)->getModality())
                {
                    $this->addSuccessMessage("<strong>Error</strong>: No podéis formar parte del mismo equipo ya que no estáis bajo la misma modalidad");

                    return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
                }
            }

            if($targetTeam = $target->getTeamForCompo($compo))
            {
                if($targetTeam === $team)
                {
                    $this->addSuccessMessage("<strong>Error:</strong> ¡este miembro ya forma parte de tu equipo!");

                    return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
                }
            }

            $teamInvitation->setSender($leader);
            $teamInvitation->setCompo($compo);
            $teamInvitation->setTeam($team);
            $teamInvitation->setType(TeamInvitation::TYPE_INVITATION);

            try
            {
                $this->persistAndFlush($teamInvitation);
            }
            catch(UniqueConstraintViolationException $ex)
            {
                $this->addSuccessMessage("<strong>Error</strong>: ¡Ya has enviado esa petición, you silly goose!");

                return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
            }

            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION, new TeamInvitationEvent($teamInvitation));

            $this->addSuccessMessage("¡Hemos la invitación a <strong>" .$teamInvitation->getTarget(). "</strong> correctamente!");
        }
        else
        {
            $this->addSuccessMessage("<strong>Error</strong>: Ocurrió un error enviando la invitación. Por favor, vuelve a intentarlo");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/aceptar-invitacion/{teamInvitation}", name="gamejam_compo_team_accept_invitation")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptInvitation(Compo $compo, TeamInvitation $teamInvitation)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        $team = $teamInvitation->getTeam();

        if($teamInvitation->getTarget() !== $user)
            throw new NotFoundHttpException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_CANCELLED, new TeamInvitationEvent($teamInvitation));

            $this->addSuccessMessage("<strong>Error</strong>: no hemos podido añadirte al equipo ya que está lleno :(");
        }
        else
        {
            $target = $teamInvitation->getTarget();
            $target->addToTeam($team);

            $this->persist($target);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_ACCEPTED, new TeamInvitationEvent($teamInvitation));

            // delete all team invitations
            $teamInvitations = $this->getRepository("GameJamCompoBundle:TeamInvitation")->findAllByUser($target);

            foreach($teamInvitations as $teamInvitation)
            {
                $this->getEntityManager()->remove($teamInvitation);
            }

            $this->flush();

            $this->addSuccessMessage("¡Ya está! ¡Ya formas parte de <strong>" .$team. "</strong>!");
        }

        return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/aceptar-peticion/{teamInvitation}", name="gamejam_compo_team_accept_request")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptRequest(Compo $compo, TeamInvitation $teamInvitation)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        $leader = $user;
        $team = $teamInvitation->getTeam();

        if($leader !== $team->getLeader())
            throw new NotFoundHttpException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_CANCELLED, new TeamInvitationEvent($teamInvitation));

            $this->addSuccessMessage("<strong>Error</strong>: el equipo está lleno :(");
        }
        else
        {
            $target = $teamInvitation->getSender();
            $target->addToTeam($team);

            $this->persist($target);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_ACCEPTED, new TeamInvitationEvent($teamInvitation));

            // delete all team invitations
            $teamInvitations = $this->getRepository("GameJamCompoBundle:TeamInvitation")->findAllByUser($target);

            foreach($teamInvitations as $teamInvitation)
            {
                $this->getEntityManager()->remove($teamInvitation);
            }

            $this->flush();

            $this->addSuccessMessage("¡Hemos añadido a <strong>" .$teamInvitation->getTarget(). "</strong> al equipo!");
        }

        return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/create", name="gamejam_compo_team_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request, Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        if($team = $user->getTeamForCompo($compo))
        {
            $this->addSuccessMessage("<strong>Error:</strong> No puedes crear un equipo ya que ya formas parte del equipo <strong>" .$team. "</strong>");

            return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
        }

        $teamCreateForm = $this->createForm(new TeamType());

        $teamCreateForm->handleRequest($request);

        if($teamCreateForm->isValid())
        {
            /** @var User $leader */
            $leader = $this->getUser();

            /** @var Team $team */
            $team = $teamCreateForm->getData();
            $team->setLeader($leader);
            $team->setCompo($compo);

            $leader->addToTeam($team);

            $this->persist($team);
            $this->persist($leader);

            // delete all team invitations
            $teamInvitations = $this->getRepository("GameJamCompoBundle:TeamInvitation")->findAllByUser($user);

            foreach($teamInvitations as $teamInvitation)
            {
                $this->getEntityManager()->remove($teamInvitation);
            }

            $this->flush();

            $this->dispatchEvent(GameJamCompoEvents::TEAM_CREATION, new TeamEvent($team, $leader));

            $this->addSuccessMessage("Acabamos de crear tu equipo. Ahora puedes invitar a más gente hasta un máximo de <strong>" .$compo->getMaxTeamMembers(). "</strong> miembros");
        }
        else
        {
            $this->addSuccessMessage("<strong>Error</strong>: Ocurrió un error creando el equipo. Por favor, vuelve a intentarlo");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/cancelar/{teamInvitation}", name="gamejam_compo_team_cancel")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function cancelInvitationAction(Compo $compo, TeamInvitation $teamInvitation)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        if($teamInvitation->isUserAbleToCancel($user))
        {
            $this->deleteAndFlush($teamInvitation);
            $this->addSuccessMessage("Hemos eliminado la petición de equipo");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/leave", name="gamejam_compo_team_leave")
     */
    public function leaveAction(Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        $team = $user->getTeamForCompo($compo);

        if($compo->isTeamFormationOpen() && $team)
        {
            if($team->getLeader() === $user)
            {
                $this->addSuccessMessage("<strong>Error:</strong> no puedes salir del equipo del cual eres el líder. ¡Deberás desbandarlo!");
            }
            else
            {
                $user->removeFromTeam($team);

                $this->persist($user);
                $this->flush();

                $this->addSuccessMessage("Te hemos eliminado del grupo correctamente");
            }
        }
        else
        {
            $this->addSuccessMessage("<strong>Error:</strong> el período de formación de equipos está cerrada. Si aun así quieres salir de tu grupo, contacta con un administrador");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/disband", name="gamejam_compo_team_disband")
     */
    public function disbandAction(Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete antes de poder gestionar tu equipo");

            return $this->redirectToPath("gamejam_compo_compo_join", ['compo' => $compo->getNameSlug()]);
        }

        if($compo->isTeamFormationOpen() && $team = $user->getTeamForCompo($compo))
        {
            if($team->getLeader() !== $user)
            {
                $this->addSuccessMessage("<strong>Error:</strong> no puedes desbandar un equipo a menos que seas el líder");
            }
            else
            {
                $this->deleteAndFlush($team);

                $this->addSuccessMessage("Equipo eliminado correctamente. Todos los miembras están fuera ahora.");
            }
        }
        else
        {
            $this->addSuccessMessage("<strong>Error:</strong> el período de formación de equipos está cerrada. Si aun así quieres salir de tu grupo, contacta con un administrador");
        }

        return $this->redirectToPath("gamejam_compo_team", ['compo' => $compo->getNameSlug()]);
    }
} 