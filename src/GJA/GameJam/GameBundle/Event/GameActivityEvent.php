<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\GameBundle\Event;

use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;
use GJA\GameJam\UserBundle\Event\UserActivityEvent;

class GameActivityEvent extends UserActivityEvent
{
    /**
     * @var Game
     */
    protected $game;

    public function __construct(User $user, Game $game = null)
    {
        parent::__construct($user);

        $this->game = $game;
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
} 