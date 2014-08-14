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
use GJA\GameJam\CompoBundle\Entity\Scoreboard;
use GJA\GameJam\CompoBundle\Form\Type\ScoreboardType;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;
use JMS\Payment\CoreBundle\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

        return ['compos' => $user->getJudgedCompos()];
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
     * @Route("/vote-game/{compo}", name="gamejam_compo_jury_vote_game")
     * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
     * @Template
     */
    public function voteGameAction(Compo $compo)
    {
        $game = $this->getRepository('GameJamGameBundle:Game')->findByCompoAndNotVotedBy($this->getUser(), $compo);

        if (is_null($game))
        {
            return ['completed' => true];
        }

        $scoreboardForm = $this->createForm(new ScoreboardType());

        return ['completed' => false, 'game' => $game, 'form' => $scoreboardForm->createView()];
    }

    /**
     * @Route("/send-score/{game}", name="gamejam_compo_jury_send_score")
     * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
     */
    public function sendScoreAction(Request $request, Game $game)
    {
        if ($game->getCompo()->getJuryVoteEndAt() > new \DateTime())
        {
            return array('result' => false);
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

                $this->persistAndFlush($scoreboard);

                return array('result' => true);
            }
        }

        return array('result' => false);
    }
} 