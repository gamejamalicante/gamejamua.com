<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\UserBundle\Entity\User;
use GJA\GameJam\UserBundle\Event\UserActivityShoutEvent;
use GJA\GameJam\UserBundle\Form\Type\ShoutType;
use GJA\GameJam\UserBundle\GameJamUserEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/panel")
 */
class PanelController extends AbstractController
{
    /**
     * @Route("/_shout", name="gamejam_user_shout")
     * @Template("GameJamUserBundle:User:_shout.html.twig")
     */
    public function shoutPartialAction(Request $request)
    {
        $activity = new Activity();
        $activity->setType(Activity::TYPE_SHOUT);

        $form = $this->createForm(new ShoutType(), $activity);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $activity->setContent(['content' => $activity->getContent()]);
                $activity->setUser($this->getUser());
                $activity->setCompo($this->getRepository("GameJamCompoBundle:Compo")->findOneBy([], ['id' => 'ASC']));

                $this->persistAndFlush($activity);

                // dispatch event
                $event = new UserActivityShoutEvent();
                $event->setUser($this->getUser());
                $event->setShout($activity);

                $this->getEventDispatcher()->dispatch(GameJamUserEvents::ACTIVITY_SHOUT, $event);

                return new JsonResponse(['success' => true]);
            }

            return new JsonResponse(['success' => false]);
        }

        return ['form' => $form->createView()];
    }
} 