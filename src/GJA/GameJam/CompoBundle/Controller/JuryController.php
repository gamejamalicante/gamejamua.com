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

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Scoreboard;
use GJA\GameJam\CompoBundle\Form\Type\ScoreboardType;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/jurado")
 */
class JuryController extends AbstractController
{
    /**
     * @Route("/votar", name="gamejam_compo_jury_vote")
     * @Template()
     */
    public function voteAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN'))
        {
            $compos = $this->getRepository('GameJamCompoBundle:Compo')->findAll();
        }
        else
        {
            $compos = $user->getJudgedCompos();
        }

        return ['compos' => $compos];
    }

    /**
     * @Route("/votar/{compo}", name="gamejam_compo_jury_vote_compo")
     * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
     * @Template
     */
    public function voteCompoAction(Compo $compo)
    {
        return ['compo' => $compo];
    }

    /**
     * @Route("/vote-game/{compo}/{game}", name="gamejam_compo_jury_vote_game", defaults={"game"=null})
     * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     * @Template
     */
    public function voteGameAction(Compo $compo, Game $game = null)
    {
        if (is_null($game))
        {
            $ignore = $this->getRequest()->get('ignore');
            $ignoreList = array();

            if (!is_null($ignore))
            {
                $ignoreList[] = $ignore;
            }

            $game = $this->getRepository('GameJamGameBundle:Game')->findByCompoAndNotVotedBy($this->getUser(), $compo, $ignoreList);
        }

        if (is_null($game) || $game->getScoreboardByVoter($this->getUser()))
        {
            return ['completed' => true];
        }

        $totalGames = $this->getRepository('GameJamGameBundle:Game')->findTotalByVotingStatus(false, $this->getUser(), $compo);
        $totalVoted = $this->getRepository('GameJamGameBundle:Game')->findTotalByVotingStatus(true, $this->getUser(), $compo);
        $progress = ($totalVoted * 100) / $totalGames;

        $scoreboardForm = $this->createForm(new ScoreboardType());

        return [
            'completed' => false,
            'game' => $game, 'form' => $scoreboardForm->createView(),
            'total_games' => $totalGames,
            'total_voted' => $totalVoted,
            'progress' => $progress
        ];
    }

    /**
     * @Route("/voted-games/{compo}", name="gamejam_compo_jury_voted_games")
     * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
     * @Template()
     */
    public function votedGamesAction(Compo $compo)
    {
        $games = $this->getRepository('GameJamGameBundle:Game')->findByCompo($compo);

        uasort($games, function($a)
        {
            return $a->getScoreboardByVoter($this->getUser()) ? 1 : -1;
        });

        return ['games' => $games];
    }

    /**
     * @Route("/send-score/{game}", name="gamejam_compo_jury_send_score")
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     */
    public function sendScoreAction(Request $request, Game $game)
    {
        if($game->getCompo()->hasJuryVotingEnded())
        {
            $response = array('result' => false, 'error' => 'Plazo de votación de terminado');

            return new JsonResponse($response);
        }

        $scoreboard = new Scoreboard();
        $scoreboardForm = $this->createForm(new ScoreboardType(), $scoreboard);

        if ($this->isPost())
        {
            $scoreboardForm->submit($request);

            if ($scoreboardForm->isValid())
            {
                $scoreboard->setGame($game);
                $scoreboard->setVoter($this->getUser());

                $this->addSuccessMessage('El juego <strong>' .$game->getName(). '</strong> ha sido votado con éxito. Continuamos con el siguiente...');

                if ($this->isGranted('ROLE_ADMIN'))
                {
                    $scoreboard->setAdmin(true);
                }

                $this->persistAndFlush($scoreboard);

                $response = array('result' => true);

                return new JsonResponse($response);
            }
        }

        $response = array('result' => false, 'error' => 'Formulario inválido');

        return new JsonResponse($response);
    }
} 