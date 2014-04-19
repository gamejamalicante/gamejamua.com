<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Achievement;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\AchievementGranted;

class LikeMasterStageOne implements GranterInterface
{
    /**
     * @param Activity $activity
     * @return AchievementGranted
     */
    public static function grant(Activity $activity)
    {
        $likes = $activity->getGame()->getLikes();

        if($likes >= 500)
        {
            $grant = new AchievementGranted();
            $grant->setGame($activity->getGame());

            return $grant;
        }

        return null;
    }
} 