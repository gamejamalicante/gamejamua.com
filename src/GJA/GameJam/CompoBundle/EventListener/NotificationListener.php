<?php

namespace GJA\GameJam\CompoBundle\EventListener;

use GJA\GameJam\CompoBundle\Event\NotificationEvent;
use GJA\GameJam\CompoBundle\Service\Mailer;

class NotificationListener
{
    const MAIL_PREFIX = "[GameJam Alicante]";

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onNotificationSent(NotificationEvent $notificationEvent)
    {
        $notification = $notificationEvent->getNotification();

        $mail = \Swift_Message::newInstance()
            ->setSubject(self::MAIL_PREFIX . " " .$notification->getTitle())
            ->setBody($notification->getContent())
            ->setContentType("text/html")
            ->setReplyTo("info@gamejamua.com");

        foreach($notificationEvent->getTargets() as $target)
        {
            $mail->setTo($target->getEmail());
        }

        $this->mailer->send($mail);
    }
} 