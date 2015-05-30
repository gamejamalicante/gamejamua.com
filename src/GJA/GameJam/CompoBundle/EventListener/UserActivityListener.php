<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\EventListener;

use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\User;
use GJA\GameJam\UserBundle\Event\UserActivityAchievementEvent;
use GJA\GameJam\UserBundle\Event\UserActivityShoutEvent;

class UserActivityListener extends AbstractActivityListener
{
    public function onShout(UserActivityShoutEvent $event)
    {
        $this->dispatchActivityEvent($event->getShout());
    }

    public function onAchievementGranted(UserActivityAchievementEvent $event)
    {
        $activity = $this->createActivity($event->getUser());
        $activity->setAchievement($event->getAchievement());
        $activity->setType(Activity::TYPE_ACHIEVEMENT);

        // add to seconds to display it after the achievement granter event :)
        $activity->getDate()->add(new \DateInterval('PT2S'));

        $this->persistActivity($activity);
    }

    protected function createActivity(User $user)
    {
        $activity = new Activity();
        $activity->setUser($user);

        return $activity;
    }
}
