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
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Event\ActivityEvent;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class AbstractActivityListener
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(EntityManager $entityManager, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function persistActivity(Activity $activity, $currentCompo = true)
    {
        if($currentCompo)
            $activity->setCompo($this->getCurrentCompo());

        $this->entityManager->persist($activity);
        $this->entityManager->flush($activity);
    }

    protected function persistAndDispatchActivity(Activity $activity, $currentCompo = true)
    {
        $this->persistActivity($activity, $currentCompo);
        $this->dispatchActivityEvent($activity);
    }

    protected function dispatchActivityEvent(Activity $activity)
    {
        $this->eventDispatcher->dispatch(GameJamCompoEvents::ACTIVITY, new ActivityEvent($activity));
    }

    protected function getCurrentCompo()
    {
        return $this->entityManager->getRepository("GameJamCompoBundle:Compo")->findOneBy([], ['id' => 'ASC']);
    }
} 