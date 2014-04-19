<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Event;

use GJA\GameJam\CompoBundle\Entity\Activity;

class UserActivityShoutEvent extends UserActivityEvent
{
    /**
     * @var Activity
     */
    protected $shout;

    /**
     * @param \GJA\GameJam\CompoBundle\Entity\Activity $shout
     */
    public function setShout($shout)
    {
        $this->shout = $shout;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Entity\Activity
     */
    public function getShout()
    {
        return $this->shout;
    }
} 