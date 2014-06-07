<?php

namespace GJA\GameJam\CompoBundle\EventSubscriber;

use GJA\GameJam\CompoBundle\Entity\Notification;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use GJA\GameJam\CompoBundle\Notifier\NotificationBuilder;
use GJA\GameJam\CompoBundle\Notifier\Notifier;
use GJA\GameJam\CompoBundle\Service\Mailer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TeamInvitationSubscriber implements EventSubscriberInterface
{
    /**
     * @var Notifier
     */
    protected $notifier;

    /**
     * @var NotificationBuilder
     */
    protected $notificationBuilder;

    public function __construct(Notifier $notifier, NotificationBuilder $notificationBuilder)
    {
        $this->notifier = $notifier;
        $this->notificationBuilder = $notificationBuilder;
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

    public function onTeamInvitation(TeamInvitationEvent $teamInvitationEvent)
    {
        $teamInvitation = $teamInvitationEvent->getTeamInvitation();

        $notification = $this->notificationBuilder
            ->createNotification(Notification::TYPE_INCLUDE_ONLY)
            ->setRenderedTitle("GameJamCompoBundle:Notification:_team_invitation_title.html.twig", ['team_invitation' => $teamInvitation])
            ->setRenderedContent("GameJamCompoBundle:Notification:_team_invitation_content.html.twig", ['team_invitation' => $teamInvitation])
            ->build();

        $this->notifier->sendNotification($notification);
    }
} 