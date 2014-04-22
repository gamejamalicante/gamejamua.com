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

class GameActivityLikeEvent extends GameActivityEvent
{
    protected $totalLikes;

    /**
     * @param mixed $totalLikes
     */
    public function setTotalLikes($totalLikes)
    {
        $this->totalLikes = $totalLikes;
    }

    /**
     * @return mixed
     */
    public function getTotalLikes()
    {
        return $this->totalLikes;
    }
} 