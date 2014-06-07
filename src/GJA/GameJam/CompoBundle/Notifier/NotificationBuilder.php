<?php

namespace GJA\GameJam\CompoBundle\Notifier;

use GJA\GameJam\CompoBundle\Entity\Notification;

class NotificationBuilder
{
    /**
     * @var Notification
     */
    protected $notification;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    public function createNotification($type)
    {
        $notification = new Notification();
        $notification->setType($type);

        $this->notification = $notification;

        return $this;
    }

    public function setRenderedTitle($template, $vars = array())
    {
        if(!$this->notification)
            throw new \InvalidArgumentException("Must create notification first");

        $title = $this->twig->render($template, $vars);

        $this->notification->setTitle($title);

        return $this;
    }

    public function setRenderedContent($template, $vars = array())
    {
        if(!$this->notification)
            throw new \InvalidArgumentException("Must create notification first");

        $content = $this->twig->render($template, $vars);

        $this->notification->setContent($content);

        return $this;
    }

    public function build()
    {
        return $this->notification;
    }
} 