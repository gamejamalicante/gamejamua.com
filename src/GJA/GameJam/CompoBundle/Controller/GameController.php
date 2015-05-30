<?php

namespace GJA\GameJam\CompoBundle\Controller;

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Form\Model\GameFilter;
use GJA\GameJam\CompoBundle\Form\Type\GameFilterType;
use GJA\GameJam\GameBundle\Repository\GameRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/nuestros-juegos")
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_games")
     * @Template()
     */
    public function indexAction()
    {
        $form = $this->createForm(new GameFilterType());

        return array('form' => $form->createView());
    }

    /**
     * @Route("/load", name="gamejam_compo_games_load")
     * @Template()
     */
    public function loadAction(Request $request)
    {
        $filter = new GameFilter();
        $form = $this->createForm(new GameFilterType(), $filter);

        $form->submit($request);

        if($form->isValid())
        {
            /** @var GameRepository $gameRepository */
            $gameRepository = $this->getRepository("GameJamGameBundle:Game");

            $games = $gameRepository->findByFilter($filter);

            return array('games' => $games);
        }

        return array('games' => array());
    }
} 