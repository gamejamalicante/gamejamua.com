<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType;

class RegisterType extends RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('nickname')
            ->add('termsAccepted', 'checkbox')
            ->add('allowCommunications', 'checkbox', ['required' => false, 'attr' => ['checked' => 'checked']]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_user_register';
    }
} 