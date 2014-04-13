<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    protected $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('message', 'textarea');

        if(!$this->user)
        {
            $builder->add('email');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_compo_contact';
    }
} 