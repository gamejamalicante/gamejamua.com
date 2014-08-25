<?php

namespace GJA\GameJam\ChallengeBundle\Event;

use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class ChallengeCompletedEvent extends Event
{
    /**
     * @var Challenge
     */
    protected $challenge;

    /**
     * @var Game
     */
    protected $game;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $extra = array();

    public function __construct(Challenge $challenge, Game $game, $user = null)
    {
        $this->challenge = $challenge;
        $this->game = $game;
        $this->user = $user;
    }

    /**
     * @param \GJA\GameJam\ChallengeBundle\Entity\Challenge $challenge
     */
    public function setChallenge($challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return \GJA\GameJam\ChallengeBundle\Entity\Challenge
     */
    public function getChallenge()
    {
        return $this->challenge;
    }

    /**
     * @param \GJA\GameJam\GameBundle\Entity\Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return \GJA\GameJam\GameBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
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

    public function addExtra($extra, $value)
    {
        $this->extra[$extra] = $value;
    }

    public function getExtra()
    {
        return $this->extra;
    }
} 