<?php

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
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
     */
    public function indexAction()
    {

    }

    /**
     * @Route("/{notification}", name="gamejam_compo_notification_view")
     */
    public function viewAction()
    {

    }

    /**
     * @Route("/unread-count", name="gamejam_compo_notifications_unread")
     */
    public function unreadCountAction()
    {
        $user = $this->getUser();

        if(!$user)
        {
            throw new AccessDeniedException;
        }
    }
} 