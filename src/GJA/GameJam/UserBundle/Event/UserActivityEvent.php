<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Event;

use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

abstract class UserActivityEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

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