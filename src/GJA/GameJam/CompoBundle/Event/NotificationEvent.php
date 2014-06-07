<?php

namespace GJA\GameJam\CompoBundle\Event;

use GJA\GameJam\CompoBundle\Entity\Notification;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class NotificationEvent extends Event
{
    /**
     * @var \GJA\GameJam\CompoBundle\Entity\Notification
     */
    protected $notification;

    /**
     * @var User[]
     */
    protected $targets;

    public function __construct(Notification $notification, $targets = array())
    {
        $this->notification = $notification;
        $this->targets = $targets;
    }

    /**
     * @return Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @return array|\GJA\GameJam\UserBundle\Entity\User[]
     */
    public function getTargets()
    {
        return $this->targets;
    }
} 