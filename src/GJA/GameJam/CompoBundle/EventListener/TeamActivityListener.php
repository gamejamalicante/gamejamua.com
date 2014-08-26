<?php

namespace GJA\GameJam\CompoBundle\EventListener;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Event\TeamEvent;
use GJA\GameJam\CompoBundle\Event\TeamInvitationEvent;
use GJA\GameJam\UserBundle\Entity\User;

class TeamActivityListener extends AbstractActivityListener
{
    public function onTeamInvitationEvent(TeamInvitationEvent $teamInvitationEvent)
    {
        $teamInvitation = $teamInvitationEvent->getTeamInvitation();

        $this->persistActivity(
            $this->createTeamActivity(
                $teamInvitation->getTeam(),
                ($teamInvitation->getType() == TeamInvitation::TYPE_INVITATION ? $teamInvitation->getTarget() : $teamInvitation->getSender()),
                Activity::TYPE_TEAM_JOIN
            )
        );
    }

    public function onTeamCreationEvent(TeamEvent $event)
    {
        $this->persistActivity(
            $this->createTeamActivity(
                $event->getTeam(),
                $event->getUser(),
                Activity::TYPE_TEAM_CREATION
            )
        );
    }

    protected function createTeamActivity(Team $team, User $user, $type)
    {
        $activity = new Activity();
        $activity->setTeam($team);
        $activity->setUser($user);
        $activity->setType($type);
        $activity->setCompo($team->getCompo());

        return $activity;
    }
}
