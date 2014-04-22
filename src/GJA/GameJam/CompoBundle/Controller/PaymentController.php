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
    public function detailsAction(Compo $compo, Order $order = null)
    {
        if(!$order)
        {
            $order = $this->createNewOrder($compo);
        }

        $routes = [
            'already_paid' => $this->redirectToPath("gamejam_compo_compo", ['compo' => $compo->getNameSlug()]),
            'payment_complete' => $this->redirectToPath("gamejam_compo_payment_complete", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()]),
            'paypal_success' => $this->generateUrl("gamejam_compo_payment_complete", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug()], true),
            'paypal_cancel' => $this->generateUrl("gamejam_compo_payment_details", ['order' => $order->getOrderNumber(), 'compo' => $compo->getNameSlug(), 'error' => true], true)
        ];

        $form = $this->createPaymentForm($order, $routes);

        if($form instanceof RedirectResponse)
            return $form;

        return ['form' => $form->createView(), 'order' => $order, 'compo' => $compo];
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
            $this->addSuccessMessage("Hemos recibido el pago del pedido. En breve te avisaremos para que puedas recogerlo en tu tienda.");
        }
        else
        {
            $this->addSuccessMessage("Ha habido un error procesando este pago. Por favor, vuelve a intentarlo");

            return $this->redirectToPath("gamejam_user_panel_payment_details", array('order' => $order->getId()));
        }

        if($result == true)
        {
            $this->get('panel_mailer')->sendPaymentConfirmedEmail($this->getUser(), $order->getOrderNumber());
            $this->get('panel_mailer')->sendShopPaymentConfirmed($order);
        }

        if($redirectSession = $this->getSession()->get('_payment_redirect'))
            return $redirectSession;

        return $this->redirectToPath("gamejam_user_panel");
    }

    private function createNewOrder($compo)
    {
        $order = new Order($this->getUser());

        $item = new CompoInscriptionItem($compo, $this->getUser());

        $order->addItem($item);

        return $order;
    }
} 