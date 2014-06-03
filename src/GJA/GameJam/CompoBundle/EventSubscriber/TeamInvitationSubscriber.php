<?php

namespace GJA\GameJam\CompoBundle\EventSubscriber;

use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use GJA\GameJam\CompoBundle\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamInvitationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Mailer
     */
    protected $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return array(
            GameJamCompoEvents::TEAM_INVITATION => 'onTeamInvitation',
            GameJamCompoEvents::TEAM_INVITATION_ACCEPTED => 'onTeamInvitationAccepted',
            GameJamCompoEvents::TEAM_INVITATION_REJECTED => 'onTeamInvitationRejected',
            GameJamCompoEvents::TEAM_REQUEST => 'onTeamRequest',
            GameJamCompoEvents::TEAM_REQUEST_ACCEPTED => 'onTeamRequestAccepted',
            GameJamCompoEvents::TEAM_REQUEST_REJECTED => 'onTeamRequestRejected'
        );
    }
} 