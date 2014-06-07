<?php

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
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
     * @Route("/enviar-peticion", name="gamejam_compo_team_send_request", defaults={"_format":"json"})
     * @Method({"POST"})
     */
    public function submitRequest(Request $request, Compo $compo)
    {
        $teamForm = $this->createForm(new TeamRequestType($compo));

        $teamForm->handleRequest($request);

        if($teamForm->isValid())
        {
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = $teamForm->getData();
            $teamInvitation->setTarget($teamInvitation->getTeam()->getLeader());
            $teamInvitation->setSender($this->getUser());
            $teamInvitation->setCompo($compo);
            $teamInvitation->setType(TeamInvitation::TYPE_REQUEST);

            try
            {
                $this->persistAndFlush($teamInvitation);
            }
            catch(UniqueConstraintViolationException $ex)
            {
                // TODO: use validator here
                return new JsonResponse(['result' => false, 'error' => '¡Ya has enviado esta petición!']);
            }

            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST, new TeamInvitationEvent($teamInvitation));

            $result = ['result' => true];
        }
        else
        {
            $result = ['result' => false, 'message' => 'Error desconocido :('];
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/enviar-invitacion", name="gamejam_compo_team_send_invitation", defaults={"_format":"json"})
     * @Method({"POST"})
     */
    public function submitInvitation(Request $request, Compo $compo)
    {
        /** @var User $leader */
        $leader = $user = $this->getUser();
        $team = $leader->getTeamForCompo($compo);

        if($leader !== $team->getLeader())
            throw new NotFoundHttpException;

        $teamInviteForm = $this->createForm(new TeamInvitationType($compo, $user));

        $teamInviteForm->handleRequest($request);

        if($teamInviteForm->isValid())
        {
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = $teamInviteForm->getData();
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
                // TODO: use validator here
                return new JsonResponse(['result' => false, 'error' => '¡Ya has enviado esta invitación!']);
            }

            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION, new TeamInvitationEvent($teamInvitation));

            $result = ['result' => true];
        }
        else
        {
            $result = ['result' => false, 'error' => 'Error desconocido :('];
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/aceptar-invitacion/{teamInvitation}", name="gamejam_compo_team_accept_invitation")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptInvitation(Compo $compo, TeamInvitation $teamInvitation)
    {
        /** @var User $user */
        $user = $this->getUser();
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

            $this->persistAndFlush($target, true);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $this->addSuccessMessage("¡Ya está! ¡Ya formas parte de <strong>" .$team. "</strong>!");
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);

        return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/aceptar-peticion/{teamInvitation}", name="gamejam_compo_team_accept_request")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptRequest(Compo $compo, TeamInvitation $teamInvitation)
    {
        $leader = $this->getUser();
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
            $target = $teamInvitation->getTarget();
            $target->addToTeam($team);

            $this->persistAndFlush($target, true);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $this->addSuccessMessage("¡Hemos añadido a <strong>" .$teamInvitation->getTarget(). "</strong> al equipo!");
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);

        return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/create", name="gamejam_compo_team_create")
     * @Method({"POST"})
     */
    public function createAction(Request $request, Compo $compo)
    {
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

            $this->flush();

            $this->dispatchEvent(GameJamCompoEvents::TEAM_CREATION, new TeamEvent($team, $leader));

            return new JsonResponse(['result' => true]);
        }

        return new JsonResponse(['result' => false, 'error' => $teamCreateForm->getErrors()]);
    }
} 