<?php

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Notification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route("/notificaciones")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_notifications")
     * @Template()
     */
    public function indexAction()
    {
        $notifications = $this->getRepository("GameJamCompoBundle:Notification")->findByUser($this->getUser());

        return ['notifications' => $notifications];
    }

    /**
     * @Route("/{notification}", name="gamejam_compo_notifications_view")
     * @ParamConverter("notification", options={"mapping":{"notification":"nameSlug"}})
     * @Template()
     */
    public function viewAction(Notification $notification)
    {
        if(!$notification->canUserReadIt($this->getUser()))
            throw new AccessDeniedException;

        if($user = $this->getUser())
        {
            if($notification->read($user))
                $this->persistAndFlush($notification, false);
        }

        return ['notification' => $notification];
    }
} 