<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\GameBundle\Event;

class GameActivityCoinsEvent extends GameActivityEvent
{
    /**
     * @var integer
     */
    protected $coins;

    protected $totalCoins;

    /**
     * @param mixed $coins
     */
    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    /**
     * @return mixed
     */
    public function getCoins()
    {
        return $this->coins;
    }

    /**
     * @param mixed $totalCoins
     */
    public function setTotalCoins($totalCoins)
    {
        $this->totalCoins = $totalCoins;
    }

    /**
     * @return mixed
     */
    public function getTotalCoins()
    {
        return $this->totalCoins;
    }
} 