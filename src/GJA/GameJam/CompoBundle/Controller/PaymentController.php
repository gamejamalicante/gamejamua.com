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

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Order\CompoInscriptionItem;
use GJA\GameJam\UserBundle\Controller\AbstractPaymentController;
use GJA\GameJam\UserBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/compo/{compo}/payment")
 * @ParamConverter("compo", options={"mapping":{"compo":"nameSlug"}})
 */
class PaymentController extends AbstractPaymentController
{
    /**
     * @Route("/details/{order}", name="gamejam_compo_payment_details", defaults={"order"=null})
     * @ParamConverter("order", options={"mapping":{"order":"orderNumber"}})
     * @Template
     */
    public function detailsAction(Request $request, Compo $compo, Order $order)
    {
        if ($request->get('change')) {
            $this->deleteAndFlush($order);

            return $this->redirectToPath('gamejam_compo_compo_join', ['compo' => $compo->getNameSlug()]);
        }

        if ($compo->isFull()) {
            $this->addSuccessMessage('<strong>Error:</strong> ¡Todas las plazas cubiertas!');

            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        $order = $this->processOrderDetails($compo, $order);

        $routes = [
            'already_paid' => $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]),
            'payment_complete' => $this->redirectToPath('gamejam_compo_payment_complete', ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()]),
            'paypal_success' => $this->generateUrl('gamejam_compo_payment_complete', ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()], true),
            'paypal_cancel' => $this->generateUrl('gamejam_compo_payment_details', ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug(), 'error' => true], true),
            'bank_account_details_route' => $this->generateUrl('gamejam_compo_bank_account_details', ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()]),
        ];

        $form = $this->createPaymentForm($order, $routes);

        if ($form instanceof RedirectResponse) {
            return $form;
        }

        return ['form' => $form->createView(), 'order' => $order, 'compo' => $compo, 'error' => $request->get('error')];
    }

    /**
     * @Route("/{order}/complete", name="gamejam_compo_payment_complete")
     * @ParamConverter("order", options={"mapping":{"order":"orderNumber"}})
     * @Template
     */
    public function completeAction(Compo $compo, Order $order)
    {
        if ($compo->isFull()) {
            $this->addSuccessMessage('<strong>Error:</strong> ¡Todas las plazas cubiertas!');

            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        $result = $this->processInternalPayment($order);
        $compoApplication = $order->getCompoApplication();

        if ($result instanceof Response) {
            // lock application
            $compoApplication->setLockTime(new \DateTime('now'));

            $this->persistAndFlush($order->getCompoApplication());

            return $result;
        } elseif ($result == true) {
            $compoApplication->setLockTime(null);
            $compoApplication->setCompleted(true);

            $this->persist($compoApplication);
            $this->persist($order->getPaymentInstruction());

            $this->flush();

            $this->addSuccessMessage('¡Pago realizado! Ya estás inscrito en la GameJam, ¡a disfrutar!');

            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        $compoApplication->setLockTime(null);
        $this->persistAndFlush($compoApplication);

        $this->addSuccessMessage('Ha habido un error procesando este pago. Por favor, vuelve a intentarlo');

        return $this->redirectToPath('gamejam_compo_payment_details', array('order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug(), 'error' => 1));
    }

    /**
     * @Route("/{order}/bank_account_details", name="gamejam_compo_bank_account_details")
     * @ParamConverter("order", options={"mapping":{"order":"orderNumber"}})
     * @Template
     */
    public function bankAccountDetailsAction(Compo $compo, Order $order)
    {
        if ($compo->isFull()) {
            $this->addSuccessMessage('<strong>Error:</strong> ¡Todas las plazas cubiertas!');

            return $this->redirectToPath('gamejam_compo_compo', ['compo' => $compo->getNameSlug()]);
        }

        return ['compo' => $compo, 'order' => $order];
    }

    private function processOrderDetails(Compo $compo, Order $order)
    {
        $item = new CompoInscriptionItem($compo, $this->getUser());

        $order->addItem($item);

        return $order;
    }
}
