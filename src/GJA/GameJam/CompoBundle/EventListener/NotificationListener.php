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

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var string
     */
    protected $replyTo;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, $replyTo)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->replyTo = $replyTo;
    }

    public function onNotificationSent(NotificationEvent $notificationEvent)
    {
        $notification = $notificationEvent->getNotification();

        if($notification->isGlobal() && !$notification->getAnnounce())

            return;

        $body = $this->twig->render("GameJamCompoBundle:Notification:mailBase.html.twig", [
            'title' => $notification->getTitle(),
            'content' => $notification->getContent()
        ]);

        $mail = \Swift_Message::newInstance()
            ->setSubject(self::MAIL_PREFIX . " " .$notification->getTitle())
            ->setBody($body)
            ->setFrom($this->replyTo)
            ->setContentType("text/html")
            ->setReplyTo($this->replyTo);

        foreach ($notificationEvent->getTargets() as $target) {
            $mail->setTo($target->getEmail());
            $this->mailer->send($mail);
        }
    }
}
