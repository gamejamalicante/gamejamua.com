<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\GameBundle\Event;

use GJA\GameJam\CompoBundle\Entity\Achievement;

class GameActivityAchievementEvent extends GameActivityEvent
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