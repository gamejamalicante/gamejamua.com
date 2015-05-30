<?php

namespace GJA\GameJam\CompoBundle\Notifier;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\Notification;
use GJA\GameJam\CompoBundle\Event\NotificationEvent;
use GJA\GameJam\CompoBundle\GameJamCompoEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Notifier
{
    /**
     * @var EntityManager
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

    public function sendNotification(Notification $notification, $generateEvent = true)
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush($notification);

        if ($generateEvent) {
            $this->eventDispatcher->dispatch(GameJamCompoEvents::NOTIFICATION_SENT, new NotificationEvent($notification));
        }
    }
}
