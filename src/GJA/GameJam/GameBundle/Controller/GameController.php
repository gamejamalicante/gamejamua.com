<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\GameBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\GameBundle\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/juegos")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/{game}", name="gamejam_game")
     * @Template()
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     */
    public function gameAction(Game $game)
    {
        return ['game' => $game];
    }
} 