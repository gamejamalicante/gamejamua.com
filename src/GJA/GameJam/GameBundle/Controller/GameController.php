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

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\GameBundle\Event\GameActivityCoinsEvent;
use GJA\GameJam\GameBundle\Event\GameActivityLikeEvent;
use GJA\GameJam\GameBundle\Form\Type\GiveCoinsType;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/juegos/{game}")
 * @ParamConverter("game", options={"mapping":{"game":"nameSlug"}})
 */
class GameController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_game")
     * @Template()
     */
    public function gameAction(Game $game)
    {
        $giveCoinsForm = null;

        /** @var User $user */
        if ($user = $this->getUser()) {
            if (!$game->isUserAllowedToEdit($user) && !$user->isAdmin()) {
                $giveCoinsForm = $this->createForm(new GiveCoinsType());
                $giveCoinsForm = $giveCoinsForm->createView();
            }
        }

        return ['game' => $game, 'give_coins_form' => $giveCoinsForm];
    }

    /**
     * @Route("/dar-monedas", name="gamejam_game_givecoins", defaults={"_format"="json"})
     * @Method({"POST"})
     */
    public function giveCoinsAction(Request $request, Game $game)
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($game->isUserAllowedToEdit($user)) {
            return new JsonResponse(['result' => false, 'coins' => $game->getCoins()]);
        }

        $giveCoinsForm = $this->createForm(new GiveCoinsType());

        $giveCoinsForm->handleRequest($request);

        if ($giveCoinsForm->isValid()) {
            $coins = (int) $giveCoinsForm->getData()['coins'];

            if ($user->getCoins() - $coins < 0) {
                return new JsonResponse(['result' => false, 'coins' => $game->getCoins(), 'error' => 'No tienes monedas suficientes :(']);
            }

            $game->giveCoins($coins);
            $user->substractCoins($coins);

            $this->persist($game);
            $this->persist($user);

            $this->flush();

            // dispatch event
            $this->dispatchEvent(GameJamGameEvents::ACTIVITY_COINS, new GameActivityCoinsEvent($user, $game, $coins));

            return new JsonResponse(['result' => true, 'coins' => $game->getCoins(), 'user_coins' => $user->getCoins()]);
        }

        return new JsonResponse(['result' => false, 'coins' => $game->getCoins(), 'error' => 'Error desconocido :(']);
    }

    /**
     * @Route("/like", name="gamejam_game_like", defaults={"_format"="json"})
     * @Method({"POST"})
     */
    public function likeAction(Game $game)
    {
        $user = $this->getUser();

        if ($game->isUserAllowedToEdit($user)) {
            return new JsonResponse(['result' => false, 'likes' => $game->getLikes()]);
        }

        $result = $game->like($user);

        if ($result) {
            // dispatch event
            $this->dispatchEvent(GameJamGameEvents::ACTIVITY_LIKES, new GameActivityLikeEvent($user, $game));

            $this->persistAndFlush($game);
        }

        return new JsonResponse(['result' => $result, 'likes' => $game->getLikes()]);
    }
}
