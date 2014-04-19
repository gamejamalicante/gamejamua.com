<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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