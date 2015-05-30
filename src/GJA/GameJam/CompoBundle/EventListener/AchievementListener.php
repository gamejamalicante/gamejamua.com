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

use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\Achievement;
use GJA\GameJam\CompoBundle\Event\ActivityEvent;
use GJA\GameJam\GameBundle\Event\GameActivityAchievementEvent;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use GJA\GameJam\UserBundle\Entity\AchievementGranted;
use GJA\GameJam\UserBundle\Event\UserActivityAchievementEvent;
use GJA\GameJam\UserBundle\GameJamUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class AchievementListener extends AbstractActivityListener
{
    public function onActivity(ActivityEvent $event)
    {
        $activity = $event->getActivity();

        /** @var Achievement[] $achievements */
        $achievements = $this->entityManager->getRepository('GameJamCompoBundle:Achievement')->findGranters();

        foreach ($achievements as $achievement) {
            // check if game already has achievement
            switch ($achievement->getType()) {
                case Achievement::TYPE_GAME:
                    if ($activity->getGame()->hasAchievement($achievement)) {
                        continue 2;
                    }
                break;

                case Achievement::TYPE_USER:
                    if ($activity->getUser()->hasAchievement($achievement)) {
                        continue 2;
                    }
                break;
            }

            if ($grant = $achievement->grant($activity)) {
                $grant->setAchievement($achievement);

                $this->entityManager->persist($grant);
                $this->entityManager->flush($grant);

                $this->dispatchAchievementEvent($grant);
            }
        }
    }

    protected function dispatchAchievementEvent(AchievementGranted $achievementGranted)
    {
        $event = null;
        $eventName = '';

        if ($user = $achievementGranted->getUser()) {
            $event = new UserActivityAchievementEvent();
            $event->setUser($user);

            $eventName = GameJamUserEvents::ACTIVITY_ACHIEVEMENT;
        } elseif ($game = $achievementGranted->getGame()) {
            $event = new GameActivityAchievementEvent();
            $event->setGame($game);

            $eventName = GameJamGameEvents::ACTIVITY_ACHIEVEMENT;
        }

        if ($event && !empty($eventName)) {
            $event->setAchievement($achievementGranted->getAchievement());
            $this->eventDispatcher->dispatch($eventName, $event);
        }
    }
}
