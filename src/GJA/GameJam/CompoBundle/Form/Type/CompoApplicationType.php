<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\CompoBundle\Entity\CompoApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CompoApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('modality', 'choice', ['choices' => CompoApplication::getAvailableModalitites()])
            ->add('nightStay');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_compo_application';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\CompoApplication'
        ]);
    }
}