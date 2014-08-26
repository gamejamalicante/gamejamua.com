<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

class BankAccountPaymentMethodType extends AbstractType
{
    public function getName()
    {
        return 'gamejam_compo_form_bank_account';
    }
}
