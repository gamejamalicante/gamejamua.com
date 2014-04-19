<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\GameBundle\Event;

use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Event\UserActivityEvent;

class GameActivityEvent extends UserActivityEvent
{
    /**
     * @var Game
     */
    protected $game;

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