<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/colaboradores")
 */
class ContributorController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_contributors")
     * @Template()
     */
    public function indexAction()
    {
        $contributors = $this->getRepository("GameJamCompoBundle:Contributor")->findBy([], ['featured' => 'DESC', 'name' => 'ASC']);

        return ['contributors' => $contributors];
    }

    /**
     * @Template("GameJamCompoBundle:Contributor:_contributors.html.twig")
     */
    public function partialContributorsAction()
    {
        $contributors = $this->getRepository("GameJamCompoBundle:Contributor")->findBy(['featured' => true], ['name' => 'ASC']);

        return ['contributors' => $contributors];
    }
}