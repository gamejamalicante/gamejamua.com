<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
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