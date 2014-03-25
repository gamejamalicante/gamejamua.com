<?php

/*
 * Copyright (c) 2013 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/")
 */
class FrontendController
{
    /**
     * @Route("/", name="gamejam_frontend")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }
}