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
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Form\Type\CompoApplicationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamInvitationType;
use GJA\GameJam\CompoBundle\Form\Type\TeamRequestType;
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
        /** @var User $user */
        $user = $this->getUser();
        $userApplication = $user ? $compo->getApplicationForUser($this->getUser()) : null;

        return [
            'user' => $user,
            'user_application' => $userApplication,
            'compo' => $compo,
            'team' => $user ? $user->getTeamForCompo($compo) : null,
            'open_formation' => $compo->isTeamFormationOpen()
        ];
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
     * @Route("/modificar-inscripcion", name="gamejam_compo_compo_modify_inscription")
     * @Template()
     */
    public function modifyApplicationAction(Request $request, Compo $compo)
    {
        /** @var User $user */
        $user = $this->getUser();

        if(!$user->hasAppliedTo($compo))
        {
            $this->addSuccessMessage("Por favor, inscríbete en la GameJam para acceder a esta sección");

            return $this->redirectToPath('gamejam_compo_compo_join', ['compo' => $compo->getNameSlug()]);
        }

        $application = $user->getApplicationTo($compo);

        if(!$application || !$application->isCompleted())
        {
            return $this->redirectToPath("gamejam_compo_payment_details", ['compo' => $compo->getNameSlug(), 'order' => $application->getOrder()->getOrderNumber()]);
        }

        $applicationForm = $this->createForm(new CompoApplicationType(), $application)
            ->remove("modality")
            ->add('edit', 'hidden');

        if($this->isPost())
        {
            $applicationForm->handleRequest($request);

            if($applicationForm->isValid())
            {
                $this->persistAndFlush($application);

                $this->addSuccessMessage("Hemos actualizado tu información de inscripción");

                return $this->redirectToPath("gamejam_compo_compo", ["compo" => $compo->getNameSlug()]);
            }
            else
            {
                $this->addSuccessMessage("<strong>Error:</strong> " . $applicationForm->getErrors());
            }
        }

        return ['form' => $applicationForm->createView(), 'user_application' => $application, 'compo' => $compo];
    }

    /**
     * @Route("/_activity", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_activity.html.twig")
     */
    public function partialLastActivityAction(Compo $compo)
    {
        $activity = $this->getRepository("GameJamCompoBundle:Activity")->findBy(['compo' => $compo], ['id' => 'DESC'], 30, 0);

        return array('activity' => $activity, 'hidden' => false);
    }

    /**
     * @Route("/_activity/{since}", name="gamejam_compo_compo_activity")
     * @Template("GameJamCompoBundle:Compo:_activity.html.twig")
     */
    public function partialActivityAction(Compo $compo, \DateTime $since)
    {
        $since->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $activity = $this->getRepository("GameJamCompoBundle:Activity")->findAllSince($since, $compo);

        return ['activity' => $activity, 'hidden' => true];
    }
} 