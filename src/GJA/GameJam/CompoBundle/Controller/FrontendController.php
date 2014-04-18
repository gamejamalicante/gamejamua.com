<?php

/*
 * Copyright (c) 2013 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Form\Type\ContactType;
use GJA\GameJam\GameBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/")
 */
class FrontendController extends AbstractController
{
    /**
     * @Route("/", name="gamejam_compo_frontend")
     * @Template()
     */
    public function indexAction()
    {
        $news = $this->getRepository("GameJamCompoBundle:Notification")->findBy(['type' => 1, 'announce' => false]);
        $activity = $this->getRepository("GameJamGameBundle:Activity")->findBy([], ['date' => 'DESC']);

        return ['news' => $news, 'activity' => $activity];
    }

    /**
     * @Route("/normas", name="gamejam_compo_frontend_rules")
     * @Template()
     */
    public function rulesAction()
    {
        return [];
    }

    /**
     * @Route("/que-es", name="gamejam_compo_frontend_about")
     * @Template()
     */
    public function aboutAction()
    {
        return [];
    }

    /**
     * @Route("/contacto/{success}", name="gamejam_compo_frontend_contact", defaults={"success"=false})
     * @Template()
     */
    public function contactAction(Request $request, $success = false)
    {
        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(new ContactType($user));

        if($this->isPost())
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $data = $form->getData();

                if($user)
                {
                    $replyTo = $user->getEmail();
                }
                else
                {
                    $replyTo = $data['email'];
                }

                // TODO: send email

                return $this->redirectToPath("gamejam_compo_frontend_contact", ['success' => true]);
            }
        }

        return ['form' => $form->createView(), 'success' => $success, 'user' => $user];
    }

    /**
     * @Route("/compos", name="gamejam_compo_frontend_compos")
     * @Template()
     */
    public function composAction()
    {
    }

    /**
     * @Route("/quienes-somos", name="gamejam_compo_frontend_staff")
     * @Template()
     */
    public function staffAction()
    {
        $staff = $this->getRepository("GameJamUserBundle:User")->findStaff();

        return ['staff' => $staff];
    }

    /**
     * @Route("/edicion-actual", name="gamejam_compo_frontend_current")
     */
    public function currentCompoAction()
    {
        /** @var Compo $compo */
        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy([], ['id' => 'ASC']);

        $response = $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
        $response->setStatusCode(302);

        return $response;
    }

    /**
     * @Route("/_partial/login", name="gamejam_compo_frontend_partial_login")
     * @Template("GameJamCompoBundle:Frontend:_login.html.twig")
     */
    public function partialLoginAction()
    {
        $csrfToken = $this->container->has('form.csrf_provider')
            ? $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate')
            : null;

        return ['csrf_token' => $csrfToken];
    }

    /**
     * @Template("GameJamCompoBundle:Frontend:_active_compo.html.twig")
     */
    public function partialActiveCompoAction()
    {
        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true]);

        return ['compo' => $compo, 'user' => $this->getUser()];
    }

    public function partialShoutAction()
    {

    }
}