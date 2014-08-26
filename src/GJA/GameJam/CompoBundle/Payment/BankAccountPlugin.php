<?php

namespace GJA\GameJam\CompoBundle\Payment;

use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;

class BankAccountPlugin extends AbstractPlugin
{
    public function approveAndDeposit(FinancialTransactionInterface $transaction, $retry)
    {
        $data = $transaction->getExtendedData();

        $actionRequest = new ActionRequiredException('User has not yet authorized the transaction.');
        $actionRequest->setFinancialTransaction($transaction);
        $actionRequest->setAction(new VisitUrl($data->get('bank_account_details_route')));

        throw $actionRequest;
    }

    public function processes($paymentSystemName)
    {
        return 'gamejam_compo_form_bank_account' === $paymentSystemName;
    }
}
