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
use GJA\GameJam\CompoBundle\Entity\Compo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/ranking/{compo}")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 */
class ScoreboardController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_scoreboard")
     * @Template
     */
    public function indexAction(Compo $compo)
    {
        $scoreboard = $this->getRepository('GameJamCompoBundle:Scoreboard')->findByCompo($compo);

        return ['scoreboard' => $scoreboard, 'compo' => $compo];
    }
}