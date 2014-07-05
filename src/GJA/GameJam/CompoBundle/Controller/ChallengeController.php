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
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\ChallengeBundle\Form\Type\ChallengeType;
use GJA\GameJam\CompoBundle\Entity\Compo;
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