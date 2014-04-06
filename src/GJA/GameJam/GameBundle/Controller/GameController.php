<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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