<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Achievement;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\AchievementGranted;

class FirstShout implements GranterInterface
{
    /**
     * @param  Activity           $activity
     * @return AchievementGranted
     */
    public static function grant(Activity $activity)
    {
        $user = $activity->getUser();

        if (count($user->getShouts()) >= 1) {
            $grant = new AchievementGranted();
            $grant->setUser($user);

            return $grant;
        }

        return null;
    }
}
