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
use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
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
        $order = $this->processOrderDetails($compo, $order);

        if($order->getPaymentInstruction()->getState() == FinancialTransactionInterface::STATE_PENDING)
        {
            return $this->redirectToPath("gamejam_compo_payment_complete", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()]);
        }

        $routes = [
            'already_paid' => $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]),
            'payment_complete' => $this->redirectToPath("gamejam_compo_payment_complete", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()]),
            'paypal_success' => $this->generateUrl("gamejam_compo_payment_complete", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()], true),
            'paypal_cancel' => $this->generateUrl("gamejam_compo_payment_details", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug(), 'error' => true], true),
            'bank_account_details_route' => $this->generateUrl("gamejam_compo_bank_account_details", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()])
        ];

        $form = $this->createPaymentForm($order, $routes);

        if($form instanceof RedirectResponse)
            return $form;

        return ['form' => $form->createView(), 'order' => $order, 'compo' => $compo, 'error' => $request->get('error')];
    }

    /**
     * @Route("/{order}/complete", name="gamejam_compo_payment_complete")
     * @ParamConverter("order", options={"mapping":{"order":"orderNumber"}})
     * @Template
     */
    public function completeAction(Compo $compo, Order $order)
    {
        $result = $this->processInternalPayment($order);

        if($result instanceof Response)
        {
            return $result;
        }
        elseif($result == true)
        {
            $order->getCompoApplication()->setCompleted(true);

            $this->persistAndFlush($order->getCompoApplication());

            $this->addSuccessMessage("¡Pago realizado! Ya estás inscrito en la GameJam, ¡a disfrutar!");

            return $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]);
        }

        $this->addSuccessMessage("Ha habido un error procesando este pago. Por favor, vuelve a intentarlo");

        return $this->redirectToPath("gamejam_compo_payment_details", array('order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug(), 'error' => 1));
    }

    /**
     * @Route("/{order}/bank_account_details", name="gamejam_compo_bank_account_details")
     * @ParamConverter("order", options={"mapping":{"order":"orderNumber"}})
     * @Template
     */
    public function bankAccountDetailsAction(Compo $compo, Order $order)
    {
        return ['compo' => $compo, 'order' => $order];
    }

    private function processOrderDetails(Compo $compo, Order $order)
    {
        $item = new CompoInscriptionItem($compo, $this->getUser());

        $order->addItem($item);

        return $order;
    }
} 