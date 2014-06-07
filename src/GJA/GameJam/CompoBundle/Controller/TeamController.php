<?php

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;
use GJA\GameJam\CompoBundle\Form\Type\TeamInvitationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamRequestType;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

            $this->persistAndFlush($teamInvitation);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST, new TeamInvitationEvent($teamInvitation));

            return new JsonResponse(['result' => true]);
        }

        return new JsonResponse(['result' => false]);
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
            throw new AccessDeniedException;

        $teamInviteForm = $this->createForm(new TeamInvitationType($compo, $user));

        $teamInviteForm->handleRequest($request);

        if($teamInviteForm->isValid())
        {
            /** @var TeamInvitation $teamInvitation */
            $teamInvitation = $teamInviteForm->getData();
            $teamInvitation->setSender($leader);
            $teamInvitation->setCompo($compo);
            $teamInvitation->setTeam($team);

            $this->persistAndFlush($teamInvitation);

            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION, new TeamInvitationEvent($teamInvitation));

            return new JsonResponse(['result' => true]);
        }

        return new JsonResponse(['result' => false, 'errors' => $teamInviteForm->getErrors()]);
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
            throw new AccessDeniedException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_CANCELLED, new TeamInvitationEvent($teamInvitation));

            $result = ["team_invitation" => "full"];
        }
        else
        {
            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $team->addMember($teamInvitation->getTarget());

            $this->persistAndFlush($team);

            $result = ["team_invitation" => "joined"];
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);

        $result += array('compo' => $compo->getNameSlug());

        return $this->redirectToPath("gamejam_compo_compo", $result);
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
            throw new AccessDeniedException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_CANCELLED, new TeamInvitationEvent($teamInvitation));

            $result = ["team_request" => "full"];
        }
        else
        {
            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $team->addMember($teamInvitation->getTarget());

            $this->persistAndFlush($team);

            $result = ["team_request" => "accepted"];
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);

        return $this->redirectToPath("gamejam_compo_compo", $result);
    }
} 