<?php

/*
 * Copyright (c) 2013 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Form\Type\ContactType;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Repository\ActivityRepository;
use GJA\GameJam\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $this->getRepository("GameJamCompoBundle:Activity");

        $news = $this->getRepository("GameJamCompoBundle:Notification")->findBy(['type' => 1], ['date' => 'DESC']);
        $activity = $activityRepository->findOnlyActivity(5);
        $messages = $activityRepository->findOnlyMessages(10);
        $twitterMentions = $activityRepository->findTwitterMentions(5);

        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true]);

        return [
            'news' => $news,
            'activity' => $activity,
            'messages' => $messages,
            'twitter_mentions' => $twitterMentions,
            'compo' => $compo
        ];
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
        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy([], ['id' => 'DESC']);

        if(!$compo)
            throw new NotFoundHttpException("No compos found!");

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
     * @Route("/_partial/notifications", name="gamejam_compo_frontend_partial_notifications")
     * @Template("GameJamCompoBundle:Frontend:_notifications.html.twig")
     */
    public function partialNotificationsAction()
    {
        $pendingNotifications = $this->getRepository("GameJamCompoBundle:Notification")->findTotalPendingByUser($this->getUser());

        return ['pending' => $pendingNotifications];
    }

    /**
     * @Template("GameJamCompoBundle:Frontend:_active_compo.html.twig")
     */
    public function partialActiveCompoAction()
    {
        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true]);

        return ['compo' => $compo, 'user' => $this->getUser()];
    }

    /**
     * @Route("/time-to-next", name="gamejam_compo_frontend_timetonext")
     */
    public function timeToNextCompoAction()
    {
        /** @var Compo $compo */
        $compo = $this->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true]);

        if($compo)
        {
           $result = array('result' => true, 'seconds' => $compo->getSecondsToStartTime());
        }
        else
        {
            $result = array('result' => false);
        }

        return new JsonResponse($result);
    }
}