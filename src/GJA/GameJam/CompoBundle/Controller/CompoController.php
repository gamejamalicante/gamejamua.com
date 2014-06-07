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

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\CompoApplication;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Form\Type\CompoApplicationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamInvitationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamType;
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

        if($application = $user->getOpenApplicationTo($compo))
        {
            return $this->redirectToPath("gamejam_compo_payment_details", ['compo' => $compo->getNameSlug(), 'order' => $application->getOrder()->getOrderNumber()]);
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
                if($application->getModality() == CompoApplication::MODALITY_FREE)
                {
                    $application->setCompleted(true);

                    $this->persistAndFlush($application);

                    return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
                }

                $order = new Order($user);
                $application->setOrder($order);

                $this->persistAndFlush($application);

                return $this->redirectToPath('gamejam_compo_payment_details', array('compo' => $compo->getNameSlug(), 'order' => $order->getOrderNumber()));
            }
        }

        return ['compo' => $compo, 'form' => $form->createView()];
    }

    /**
     * @Route("/_team", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_team.html.twig")
     */
    public function partialTeamAction(Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($team = $user->getTeamForCompo($compo))
        {
            if($team->getLeader() === $user)
            {
                $teamForm = $this->createForm(new TeamInvitationType(TeamInvitation::TYPE_INVITATION, $user));

                return ['invite_form' => $teamForm->createView()];
            }
            else
            {
                // TODO: leave team forms
            }
        }
        else
        {
            $teamCreateForm = $this->createForm(new TeamType($user, $compo));
            $teamForm = $this->createForm(new TeamInvitationType(TeamInvitation::TYPE_REQUEST, $user));

            return ['creation_form' => $teamCreateForm->createView(), 'request_form' => $teamForm->createView()];
        }
    }

    /**
     * @Route("/_activity", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_activity.html.twig")
     */
    public function partialLastActivityAction(Compo $compo)
    {
        $activity = $this->getRepository("GameJamCompoBundle:Activity")->findBy(['compo' => $compo], ['id' => 'DESC'], 30, 0);

        return array('activity' => $activity);
    }

    /**
     * @Route("/_activity/{since}", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_activity.html.twig")
     */
    public function partialActivityAction(Compo $compo, \DateTime $since)
    {
        if(is_null($since))
        {

        }

        $since->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $activity = $this->getRepository("GameJamCompoBundle:Activity")->findAllSince($since, $compo);

        return ['activity' => $activity];
    }
} 