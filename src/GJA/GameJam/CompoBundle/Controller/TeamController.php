<?php

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/compos/{compo}/{team}")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 * @ParamConverter("team", options={"mapping":{"team":"nameSlug"}})
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/enviar-invitacion/{user}", name="gamejam_compo_team_send_invitation")
     */
    public function sendInvitation(Compo $compo, Team $team, User $user)
    {
        /** @var User $leader */
        $leader = $this->getUser();

        if($leader !== $team->getLeader())
            throw new AccessDeniedException;

        if($leader === $user)
            throw new \InvalidArgumentException;

        // create team invitation
        $teamInvitation = new TeamInvitation();
        $teamInvitation->setType(TeamInvitation::TYPE_INVITATION);
        $teamInvitation->setSender($leader);
        $teamInvitation->setTarget($user);
        $teamInvitation->setCompo($compo);

        // persist invitation
        $this->persistAndFlush($teamInvitation);

        // dispach event
        $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION, new TeamInvitationEvent($teamInvitation));
    }

    /**
     * @Route("/aceptar-invitacion/{teamInvitation}", name="gamejam_compo_team_accept_invitation")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptInvitation(Compo $compo, Team $team, TeamInvitation $teamInvitation)
    {
        $user = $this->getUser();

        if($teamInvitation->getTarget() !== $user)
            throw new AccessDeniedException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_CANCELLED, new TeamInvitationEvent($teamInvitation));
        }
        else
        {
            $this->dispatchEvent(GameJamCompoEvents::TEAM_INVITATION_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $team->addMember($teamInvitation->getTarget());
            $this->persistAndFlush($team);
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);
    }

    /**
     * @Route("/enviar-peticion/{user}", name="gamejam_compo_team_send_request")
     */
    public function sendRequest(Compo $compo, Team $team)
    {
        $sender = $this->getUser();
        $leader = $team->getLeader();

        if($leader === $sender)
            throw new \InvalidArgumentException;

        $teamInvitation = new TeamInvitation();
        $teamInvitation->setSender($sender);
        $teamInvitation->setTarget($leader);
        $teamInvitation->setTeam($team);
        $teamInvitation->setCompo($compo);
        $teamInvitation->setType(TeamInvitation::TYPE_REQUEST);

        $this->persistAndFlush($teamInvitation);

        $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST, new TeamInvitationEvent($teamInvitation));
    }

    /**
     * @Route("/aceptar-peticion/{teamInvitation}", name="gamejam_compo_team_accept_request")
     * @ParamConverter("teamInvitation", options={"mapping":{"teamInvitation":"hash"}})
     */
    public function acceptRequest(Compo $compo, Team $team, TeamInvitation $teamInvitation)
    {
        $leader = $this->getUser();

        if($leader !== $team->getLeader())
            throw new AccessDeniedException;

        if($team->isFull())
        {
            // team is full, cancel invitation
            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_CANCELLED, new TeamInvitationEvent($teamInvitation));
        }
        else
        {
            $this->dispatchEvent(GameJamCompoEvents::TEAM_REQUEST_ACCEPTED, new TeamInvitationEvent($teamInvitation));
            $team->addMember($teamInvitation->getTarget());

            $this->persistAndFlush($team);
        }

        // delete invitation
        $this->deleteAndFlush($teamInvitation);
    }
} 