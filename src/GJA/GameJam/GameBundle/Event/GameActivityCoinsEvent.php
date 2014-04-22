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