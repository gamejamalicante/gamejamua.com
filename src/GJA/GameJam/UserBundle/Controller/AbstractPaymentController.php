<?php

/*
 * Copyright (c) 2013 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Controller;

use Certadia\Library\Controller\AbstractController;
use Certadia\Library\Controller\GoogleHelperTrait;
use GJA\GameJam\UserBundle\Entity\Order;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use JMS\Payment\CoreBundle\PluginController\Result;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPaymentController extends AbstractController
{
    protected function createPaymentForm(Order $order, array $routes, callable $success = null)
    {
        if($order->getUser() !== $this->getUser())
            throw new AccessDeniedException;

        if($order->isPaid())
        {
            return $routes['already_paid'];
        }

        $pluginController = $this->get('payment.plugin_controller');

        /** @var Form $form */
        $form = $this->createForm('jms_choose_payment_method', null, array(
            'amount' => $order->getPaypalAmount(),
            'currency' => 'EUR',
            'default_method' => 'paypal_express_checkout',
            'predefined_data' => array(
                'paypal_express_checkout' => array(
                    'return_url' => $routes['paypal_success'],
                    'cancel_url' => $routes['paypal_cancel'],
                    'checkout_params' => $this->getPaypalItemDescription($order)
                ),
            ),
        ));

        if ($this->isPost())
        {
            $form->handleRequest($this->getRequest());

            if($form->isValid())
            {
                $pluginController->createPaymentInstruction($instruction = $form->getData());

                $order->setPaymentInstruction($instruction);

                if($success)
                    $success($order);

                $this->persistAndFlush($order);

                return $routes['payment_complete'];
            }
        }

        return $form;
    }

    protected function processInternalPayment(Order $order)
    {
        $instruction = $order->getPaymentInstruction();
        $pluginController = $this->get('payment.plugin_controller');

        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {
            $payment = $pluginController->createPayment($instruction->getId(), $instruction->getAmount() - $instruction->getDepositedAmount());
        } else {
            $payment = $pendingTransaction->getPayment();
        }

        /** @var Result $result */
        $result = $pluginController->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

        if(Result::STATUS_PENDING === $result->getStatus())
        {
            $ex = $result->getPluginException();

            if($ex instanceof ActionRequiredException)
            {
                $action = $ex->getAction();

                if($action instanceof VisitUrl)
                {
                    return new RedirectResponse($action->getUrl());
                }

                throw $ex;
            }
        }
        elseif(Result::STATUS_SUCCESS !== $result->getStatus())
        {
            return false;
        }

        return true;
    }

    protected function getPaypalItemDescription(Order $order)
    {
        $paypalConfig = [];
        $number = 0;

        foreach($order->getItems() as $number => $item)
        {
            $paypalConfig['L_PAYMENTREQUEST_0_NAME' . $number] = $item->getDescription();
            $paypalConfig['L_PAYMENTREQUEST_0_QTY' . $number] = $item->getQuantity();
            $paypalConfig['L_PAYMENTREQUEST_0_AMT' . $number] = (string) $item->getAmount();

            $number++;
        }

        $paypalConfig['L_PAYMENTREQUEST_0_NAME' . $number] = "Cargo servicio PayPal";
        $paypalConfig['L_PAYMENTREQUEST_0_QTY' . $number] = 1;
        $paypalConfig['L_PAYMENTREQUEST_0_AMT' . $number] = (string) $order->getPaypalCommission();

        $paypalConfig['PAGESTYLE'] = "Gamejam";

        return $paypalConfig;
    }
} 