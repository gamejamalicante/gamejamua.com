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
