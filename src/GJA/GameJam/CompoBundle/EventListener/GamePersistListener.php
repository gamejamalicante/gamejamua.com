<?php

namespace GJA\GameJam\CompoBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use GJA\GameJam\GameBundle\Entity\Download;
use GJA\GameJam\GameBundle\Entity\Media;
use GJA\GameJam\GameBundle\Event\GameActivityDownloadEvent;
use GJA\GameJam\GameBundle\Event\GameActivityMediaEvent;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class GamePersistListener
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if($entity instanceof Media)
        {
            if($game = $entity->getGame())
                $this->eventDispatcher->dispatch(GameJamGameEvents::ACTIVITY_MEDIA, new GameActivityMediaEvent($game, $entity));
        }
        elseif($entity instanceof Download)
        {
            if($game = $entity->getGame())
                $this->eventDispatcher->dispatch(GameJamGameEvents::ACTIVITY_DOWNLOAD, new GameActivityDownloadEvent($game, $entity));
        }
    }
} 