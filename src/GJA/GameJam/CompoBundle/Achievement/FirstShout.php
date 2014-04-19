<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Achievement;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\AchievementGranted;

class FirstShout implements GranterInterface
{
    /**
     * @param Activity $activity
     * @return AchievementGranted
     */
    public static function grant(Activity $activity)
    {
        $user = $activity->getUser();

        if(count($user->getShouts()) >= 1)
        {
            $grant = new AchievementGranted();
            $grant->setUser($user);

            return $grant;
        }

        return null;
    }
} 