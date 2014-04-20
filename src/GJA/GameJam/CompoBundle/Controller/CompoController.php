<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\CompoApplication;
use GJA\GameJam\CompoBundle\Form\Type\CompoApplicationType;
use GJA\GameJam\CompoBundle\Order\CompoInscriptionItem;
use GJA\GameJam\UserBundle\Entity\Order;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/compos/{compo}")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 */
class CompoController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_compo")
     * @Template()
     */
    public function indexAction(Compo $compo)
    {
        $user = $this->getUser();
        $userApplication = $user ? $compo->getApplicationForUser($this->getUser()) : null;

        return ['user' => $user, 'user_application' => $userApplication, 'compo' => $compo];
    }

    /**
     * @Route("/inscribirse", name="gamejam_compo_compo_join")
     * @Template
     */
    public function joinAction(Compo $compo, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user->hasAppliedTo($compo))
        {
            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        $application = new CompoApplication();
        $application->setCompo($compo);
        $application->setUser($user);
        $application->setCompleted(false);

        $form = $this->createForm(new CompoApplicationType(), $application);

        if($this->isPost())
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $this->persistAndFlush($application);

                return $this->redirectToPath('gamejam_compo_payment_details', array('compo' => $compo->getNameSlug()));
            }
        }

        return ['compo' => $compo, 'form' => $form->createView()];
    }

    /**
     * @Route("/_activity/{since}", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_activity.html.twig")
     */
    public function partialActivityAction(Compo $compo, \DateTime $since)
    {
        $since->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $activity = $this->getRepository("GameJamCompoBundle:Activity")->findAllSince($since, $compo);

        return ['activity' => $activity];
    }
} 