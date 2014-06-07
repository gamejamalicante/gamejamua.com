<?php

namespace GJA\GameJam\CompoBundle\EventListener;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;

class TeamActivityListener extends AbstractActivityListener
{
    public function onTeamInvitationEvent(TeamInvitationEvent $teamInvitationEvent)
    {
        $teamInvitation = $teamInvitationEvent->getTeamInvitation();

        $activity = new Activity();
        $activity->setUser($teamInvitation->getTarget());
        $activity->setCompo($teamInvitation->getCompo());
        $activity->setType(Activity::TYPE_TEAM);
        $activity->setTeam($teamInvitation->getTeam());

        $this->persistActivity($activity);
    }
} 