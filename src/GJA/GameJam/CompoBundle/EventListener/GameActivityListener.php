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
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\GameBundle\Event\GameActivityAchievementEvent;
use GJA\GameJam\GameBundle\Event\GameActivityCoinsEvent;
use GJA\GameJam\GameBundle\Event\GameActivityCreationEvent;
use GJA\GameJam\GameBundle\Event\GameActivityDownloadEvent;
use GJA\GameJam\GameBundle\Event\GameActivityInfoUpdateEvent;
use GJA\GameJam\GameBundle\Event\GameActivityLikeEvent;
use GJA\GameJam\GameBundle\Event\GameActivityMediaEvent;
use GJA\GameJam\UserBundle\Entity\User;

class GameActivityListener extends AbstractActivityListener
{
    public function onCoins(GameActivityCoinsEvent $event)
    {
        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setType(Activity::TYPE_COINS);
        $activity->setContent(['coins' => $event->getCoins(), 'total_coins' => $event->getTotalCoins()]);

        $this->persistAndDispatchActivity($activity);
    }

    public function onCreation(GameActivityCreationEvent $event)
    {
        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setType(Activity::TYPE_CREATION);

        $this->persistAndDispatchActivity($activity);
    }

    public function onInfoUpdate(GameActivityInfoUpdateEvent $event)
    {
        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setType(Activity::TYPE_INFO_UPDATE);

        $this->persistAndDispatchActivity($activity);
    }

    public function onLike(GameActivityLikeEvent $event)
    {
        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setType(Activity::TYPE_LIKES);
        $activity->setContent(['total_likes' => $event->getTotalLikes()]);

        $this->persistAndDispatchActivity($activity);
    }

    public function onMedia(GameActivityMediaEvent $event)
    {
        if ($event->getGame()->isNew()) {
            return;
        }

        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setMedia($event->getMedia());
        $activity->setType(Activity::TYPE_MEDIA);

        $this->persistAndDispatchActivity($activity);
    }

    public function onDownload(GameActivityDownloadEvent $event)
    {
        if ($event->getGame()->isNew()) {
            return;
        }

        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setDownload($event->getDownload());
        $activity->setType(Activity::TYPE_DOWNLOAD);

        $this->persistAndDispatchActivity($activity);
    }

    public function onAchievementGranted(GameActivityAchievementEvent $event)
    {
        $activity = $this->createActivity($event->getGame(), $event->getUser());
        $activity->setType(Activity::TYPE_ACHIEVEMENT);
        $activity->setAchievement($event->getAchievement());

        $this->persistActivity($activity);
    }

    protected function createActivity(Game $game, User $user = null)
    {
        $activity = new Activity();
        $activity->setGame($game);
        $activity->setUser($user);

        return $activity;
    }
}
