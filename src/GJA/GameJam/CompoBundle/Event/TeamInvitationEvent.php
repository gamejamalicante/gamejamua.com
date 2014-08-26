<?php

namespace GJA\GameJam\CompoBundle\Event;

use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use Symfony\Component\EventDispatcher\Event;

class TeamInvitationEvent extends Event
{
    /**
     * @var TeamInvitation
     */
    protected $teamInvitation;

    public function __construct(TeamInvitation $teamInvitation)
    {
        $this->teamInvitation = $teamInvitation;
    }

    /**
     * @param \GJA\GameJam\CompoBundle\Entity\TeamInvitation $teamInvitation
     */
    public function setTeamInvitation($teamInvitation)
    {
        $this->teamInvitation = $teamInvitation;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Entity\TeamInvitation
     */
    public function getTeamInvitation()
    {
        return $this->teamInvitation;
    }
}
