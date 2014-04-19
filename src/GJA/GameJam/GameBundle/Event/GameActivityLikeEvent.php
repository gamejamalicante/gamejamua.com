<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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