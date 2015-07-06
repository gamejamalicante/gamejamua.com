<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Controller;

use GJA\GameJam\CompoBundle\Entity\Compo;
use Gregwar\Image\Image;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{user}", name="gamejam_user_profile")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     * @Template
     */
    public function profileAction(User $user)
    {
        return ['user' => $user];
    }

    /**
     * @Route("/{user}/ticket", name="gamejam_user_profile")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     */
    public function ticketAction(User $user)
    {
        if (!$this->isGrantedRole("ROLE_ADMIN") && $user !== $this->getUser()) {
            throw new AccessDeniedException;
        }

        $compo = $this->getRepository('GameJamCompoBundle:Compo')->findRunningCompo();

        if (is_null($compo) || !$user->hasAppliedTo($compo)) {
            return new JsonResponse();
        }

        return $this->generateTicket($user, $compo);
    }

    protected function generateTicket(User $user, Compo $compo)
    {
        $ticketFile = __DIR__.'/../Resources/badges/ticket.jpg';
        $fontIdPrice = __DIR__.'/../Resources/badges/adampro.otf';
        $fontName = __DIR__.'/../Resources/badges/pacifico.ttf';

        $application = $compo->getApplicationForUser($user);

        $image = new Image($ticketFile);
        $image->useFallback(false);

        $this->drawUsername($image, $user, $fontName);
        $this->drawPrice($image, $user, $fontIdPrice, $application->getOrder()->getAmount());

        $imageData = $image->get('jpg', 100);

        return new Response(
            $imageData,
            200,
            array(
                'Content-Type' => 'image/jpeg'
            )
        );
    }

    protected function drawUsername(Image $image, User $user, $fontFile)
    {
        $image->write($fontFile, '@' . $user->getUsername(), 2520, 390, 40, 0, 0x000000, 'right');
    }

    protected function drawPrice(Image $image, User $user, $fontFile, $price)
    {
        $image->write($fontFile, $price, 2290, 488, 60, 0, 0x000000, 'right');
    }
}
