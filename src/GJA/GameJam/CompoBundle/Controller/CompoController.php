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

        $form = $this->createForm(new CompoApplicationType(), $application);

        if($this->isPost())
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $this->persistAndFlush($application);

                return $this->redirectToPath('gamejam_compo_compo_application', array('compo' => $compo->getNameSlug(), 'first' => true));
            }
        }

        return ['compo' => $compo, 'form' => $form->createView()];
    }
} 