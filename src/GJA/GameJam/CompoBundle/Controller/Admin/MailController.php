<?php

namespace GJA\GameJam\CompoBundle\Controller\Admin;

use Certadia\Library\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/admin/mail")
 */
class MailController extends AbstractController
{
    /**
     * @Route("/preview/{template}")
     */
    public function previewAction($template)
    {
        return $this->render("GameJamCompoBundle:Mail:" . $template. ".html.twig");
    }
}
