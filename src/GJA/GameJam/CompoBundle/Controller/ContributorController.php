<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
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
        $contributors = $this->getRepository("GameJamCompoBundle:Contributor")->findBy([], ['featured' => 'DESC']);

        return ['contributors' => $contributors];
    }
}