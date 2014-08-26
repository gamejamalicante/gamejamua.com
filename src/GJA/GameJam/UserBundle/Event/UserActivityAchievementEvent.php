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

use GJA\GameJam\CompoBundle\Entity\Achievement;

class UserActivityAchievementEvent extends UserActivityEvent
{
    /**
     * @var Achievement
     */
    protected $achievement;

    /**
     * @param \GJA\GameJam\CompoBundle\Entity\Achievement $achievement
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Entity\Achievement
     */
    public function getAchievement()
    {
        return $this->achievement;
    }
}
