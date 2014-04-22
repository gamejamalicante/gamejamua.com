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

class CoinMasterStageOne implements GranterInterface
{
    /**
     * @param Activity $activity
     * @return AchievementGranted
     */
    public static function grant(Activity $activity)
    {
        $coins = $activity->getGame()->getCoins();

        if($coins >= 500)
        {
            $grant = new AchievementGranted();
            $grant->setUser($activity->getUser());

            return $grant;
        }

        return null;
    }
} 