<?php

namespace GJA\GameJam\UserBundle\Form\Type;

use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname', null, ['required' => false])
            ->add('avatarUrl', 'text', ['required' => false])
            ->add('birthDate', null, ['required' => false, 'years' => range(date("Y") - 50, date("Y")-12)])
            ->add('sex', 'choice', ['required' => false, 'choices' => User::getAvailableSexes(), 'expanded' => false, 'multiple' => false])
            ->add('siteUrl', null, ['required' => false])
            ->add('city', null, ['required' => false])
            ->add('presentation', null, ['required' => false])
            ->add('publicProfile', null, ['required' => false])
            ->add('publicEmail', null, ['required' => false])
            ->add('allowCommunications', null, ['required' => false]);
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_user_profile';
    }
}