<?php

namespace GJA\GameJam\CompoBundle\Event;

use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class TeamEvent extends Event
{
    /**
     * @var \GJA\GameJam\CompoBundle\Entity\Team
     */
    protected $team;

    /**
     * @var \GJA\GameJam\UserBundle\Entity\User
     */
    protected $user;

    public function __construct(Team $team, User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * @param \GJA\GameJam\CompoBundle\Entity\Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Entity\Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param \GJA\GameJam\UserBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \GJA\GameJam\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
} 