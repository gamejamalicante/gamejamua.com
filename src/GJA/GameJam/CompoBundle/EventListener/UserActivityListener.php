<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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
        $activity->getDate()->add(new \DateInterval("PT2S"));

        $this->persistActivity($activity);
    }

    protected function createActivity(User $user)
    {
        $activity = new Activity();
        $activity->setUser($user);

        return $activity;
    }
} 