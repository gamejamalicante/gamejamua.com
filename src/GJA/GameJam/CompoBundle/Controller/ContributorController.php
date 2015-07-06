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

use GJA\GameJam\CompoBundle\Entity\Contributor;
use TrivialSense\FrameworkCommon\Controller\AbstractController;
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
        $sponsors = $this->getRepository('GameJamCompoBundle:Contributor')->findSponsors();
        $juries = $this->getRepository('GameJamCompoBundle:Contributor')->findJuries();

        return ['sponsors' => $sponsors, 'juries' => $juries];
    }

    /**
     * @Template("GameJamCompoBundle:Contributor:_contributors.html.twig")
     */
    public function partialContributorsAction()
    {
        $contributors = $this->getRepository('GameJamCompoBundle:Contributor')->findSponsors();

        $geekyGames = new Contributor();
        $geekyGames->setUrl('https://geekyjuegos.com/');
        $geekyGames->setName('Geeky Games');
        $geekyGames->setNameSlug('geeky');

        $contributors[] = $geekyGames;

        return ['contributors' => $contributors];
    }
}
