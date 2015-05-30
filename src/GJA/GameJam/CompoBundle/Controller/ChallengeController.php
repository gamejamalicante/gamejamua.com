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
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\ChallengeBundle\Form\Type\ChallengeType;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * @Route("/compos/{compo}/challenges")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 */
class ChallengeController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_compo_challenges")
     * @Template()
     */
    public function indexAction(Compo $compo, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage('No puedes acceder a los retos solidarios ya que no formas parte de la GameJam :(');

            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        $form = $this->createForm(new ChallengeType());

        if($this->isPost())
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                /** @var Challenge $challenge */
                $challenge = $form->getData();
                $challenge->setUser($user);

                $runningCompo = $this->getRepository('GameJamCompoBundle:Compo')->findRunningCompo();

                if($runningCompo === null)
                {
                    $this->addSuccessMessage('Los retos solidarios estarán disponibles cuando empiece la GameJam :)');
                    return $this->redirectToPath('gamejam_compo_compo_challenges', ['compo' => $compo->getNameSlug()]);
                }

                $team = $user->getTeamForCompo($runningCompo);

                if(is_null($team)) {
                    // user is solo, check game created
                    /** @var Game $game */
                    $game = $this->getRepository('GameJamGameBundle:Game')->findOneBy(['compo' => $runningCompo, 'user' => $user]);
                } else {
                    $game = $this->getRepository('GameJamGameBundle:Game')->findOneBy(['compo' => $runningCompo, 'team' => $team]);
                }

                if(!is_null($game)) {
                    $challenge->setGame($game);
                } else {
                    $createGameRoute = $this->generateUrl('gamejam_game_panel_create');
                    $this->addSuccessMessage('Para crear retos necesitas primero crear un juego. <a href="' .$createGameRoute. '">Pulsa aquí para crearlo</a>');
                    return $this->redirectToPath('gamejam_compo_compo_challenges', ['compo' => $compo->getNameSlug()]);
                }

                $this->persistAndFlush($challenge);

                $this->addSuccessMessage('¡Reto creado con éxito! El token del reto es: <strong><code>' .$challenge->getToken(). '</code></strong>');

                return $this->redirectToPath('gamejam_compo_compo_challenges', ['compo' => $compo->getNameSlug(), 'challenge' => $challenge->getToken()]);
            }
        }

        $challenges = $this->getRepository('GameJamChallengeBundle:Challenge')->findBy(
            ['user' => $this->getUser()], ['updatedAt' => 'DESC']
        );

        return [
            'form' => $form->createView(),
            'challenges' => $challenges,
            'compo' => $compo,
            'challenge' => $request->get('challenge')
        ];
    }

    /**
     * @Route("/delete/{challenge}", name="gamejam_compo_compo_challenges_delete")
     * @ParamConverter("challenge", options={"mapping":{"challenge":"token"}})
     */
    public function deleteAction(Compo $compo, Challenge $challenge)
    {
        /** @var User $user */
        $user = $this->getUser();
        $runningCompo = $this->getRepository('GameJamCompoBundle:Compo')->findRunningCompo();

        if($runningCompo) {
            /** @var Game $game */
            $game = $challenge->getGame();

            if($game->isUserAllowedToEdit($user))
            {
                return $this->deleteChallenge($compo, $challenge);
            }
        }

        if ($challenge->getUser() === $user)
        {
            return $this->deleteChallenge($compo, $challenge);
        }

        $this->addSuccessMessage('No tienes permisos para borrar el reto');
        return $this->redirectToPath('gamejam_compo_compo_challenges', ['compo' => $compo->getNameSlug()]);
    }


    protected function deleteChallenge(Compo $compo, Challenge $challenge)
    {
        $this->deleteAndFlush($challenge);
        $this->addSuccessMessage('El reto ha sido borrado con éxito');
        return $this->redirectToPath('gamejam_compo_compo_challenges', ['compo' => $compo->getNameSlug()]);
    }

    /**
     * @Route("/{challenge}/_info", name="gamejam_compo_compo_challenges_info")
     * @ParamConverter("challenge", options={"mapping":{"challenge":"token"}})
     * @Template("GameJamCompoBundle:Challenge:_challenge_info.html.twig")
     */
    public function partialShowInfoAction(Compo $compo, Challenge $challenge)
    {
        $endPoint = $this->generateUrl('gamejam_api_challenge_complete', ['challenge' => $challenge->getToken()], UrlGenerator::ABSOLUTE_URL);

        return ['challenge' => $challenge, 'endpoint' => $endPoint];
    }
} 