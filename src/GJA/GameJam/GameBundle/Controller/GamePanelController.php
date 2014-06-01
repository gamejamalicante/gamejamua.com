<?php

namespace GJA\GameJam\GameBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\GameBundle\Entity\Game;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/panel/juegos")
 */
class GamePanelController extends AbstractController
{
    /**
     * @Route("/crear", name="gamejam_game_panel_create")
     * @Template()
     */
    public function createAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/editar/{game}", name="gamejam_game_panel_edit")
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     * @Template()
     */
    public function editAction(Request $request, Game $game)
    {
        return [];
    }
}