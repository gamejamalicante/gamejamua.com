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
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{user}/ticket/show", name="gamejam_user_profile_ticket_show")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     * @Template
     */
    public function ticketShowAction(User $user, Request $request)
    {
        $token = $request->get('token');

        if (!$this->isGrantedRole("ROLE_ADMIN") && $user->getAutologinToken() != $token) {
            throw new AccessDeniedException;
        }

        $imageUrl = $this->generateUrl('gamejam_user_profile_ticket', [
            'user' => $user->getUsername(),
            'token' => $user->getAutologinToken()
        ], true);

        return array('image' => $imageUrl);
    }

    /**
     * @Route("/{user}/ticket", name="gamejam_user_profile_ticket")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     */
    public function ticketAction(User $user, Request $request)
    {
        $token = $request->get('token');

        if (!$this->isGrantedRole("ROLE_ADMIN") && $user->getAutologinToken() != $token) {
            throw new AccessDeniedException;
        }

        $compo = $this->getRepository('GameJamCompoBundle:Compo')->findOneBy([], ['id' => 'DESC']);

        if (is_null($compo) || !$user->hasAppliedTo($compo)) {
            return new JsonResponse();
        }

        return $this->generateTicket($user, $compo);
    }

    /**
     * @Route("/{user}/ticket/pdf", name="gamejam_user_profile_ticket_pdf")
     * @ParamConverter("user", options={"mapping":{"user":"username"}})
     */
    public function singlePdfAction(User $user, Request $request)
    {
        $token = $request->get('token');

        if ($user->getAutologinToken() != $token && $user != $this->getUser()) {
            throw new AccessDeniedException;
        }

        $pageUrl = $this->generateUrl('gamejam_user_profile_ticket_show', [
            'user' => $user->getUsername(),
            'token' => $user->getAutologinToken()
        ], true);

        return new Response(
            $this->get('knp_snappy.pdf')->getOutput($pageUrl),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' .$user->getUsername(). '.pdf""'
            ]
        );
    }

    protected function generateTicket(User $user, Compo $compo)
    {
        $ticketFile = __DIR__.'/../Resources/badges/ticket.jpg';
        $fontIdPrice = __DIR__.'/../Resources/badges/adampro.otf';
        $fontName = __DIR__.'/../Resources/badges/pacifico.ttf';

        $application = $compo->getApplicationForUser($user);

        $members = $compo->getJoinedMembers();
        $memberNumber = null;

        foreach ($members as $key => $member) {
            if ($member == $user) {
                $memberNumber = str_pad($key+1, 3, '0', STR_PAD_LEFT);
            }
        }

        $image = new Image($ticketFile);
        $image->useFallback(false);

        try {
            $price = $application->getOrder()->getAmount();
        } catch (\InvalidArgumentException $e) {
            $price = 6;
        }

        $this->drawUsername($image, $user, $fontName);
        $this->drawPrice($image, $user, $fontIdPrice, $price);
        $this->drawNumber($image, $memberNumber, $fontIdPrice);

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

    protected function drawNumber(Image $image, $memberNumber, $fontFile)
    {
        $image->write($fontFile, '#' . $memberNumber, 3400, 128, 80, 0, 0xFFFFFF, 'right');
        $image->write($fontFile, '#' . $memberNumber, 290, 1390, 80, 0, 0xFFFFFF, 'right');
    }
}
