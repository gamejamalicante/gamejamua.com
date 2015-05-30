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

use TrivialSense\FrameworkCommon\Controller\AbstractController;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Service\LinkUnshortener;
use GJA\GameJam\UserBundle\Entity\User;
use GJA\GameJam\UserBundle\Event\UserActivityShoutEvent;
use GJA\GameJam\UserBundle\Form\Type\ProfileType;
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
     * @Route("/", name="gamejam_user_panel")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/editar-perfil", name="gamejam_user_panel_edit")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(new ProfileType(), $user);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);

            if($form->isValid())
            {
                $this->persistAndFlush($user);

                $this->addSuccessMessage("¡Perfil actualizado!");

                $this->redirectToPath("gamejam_user_panel_edit", ['success' => true]);
            }
        }

        return ['form' => $form->createView(), 'user' => $user];
    }

    /**
     * @Route("/_shout", name="gamejam_user_panel_shout")
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
                /** @var LinkUnshortener $linkUnshortener */
                $linkUnshortener = $this->get('gamejam.compo.link_unshortener');

                $content = $linkUnshortener->findAndUnshortenLinks($activity->getContent());

                $activity->setContent(['content' => $content]);
                $activity->setUser($this->getUser());
                $activity->setCompo($this->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true], ['id' => 'ASC']));

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