<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WaitingListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('token', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\WaitingList'
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_compo_waitinglist';
    }
}
