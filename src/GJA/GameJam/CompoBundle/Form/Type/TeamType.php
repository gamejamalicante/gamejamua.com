<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\CompoBundle\Entity\Compo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
    }

    public function getName()
    {
        return 'gamejam_compo_team';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\Team'
        ]);
    }
} 