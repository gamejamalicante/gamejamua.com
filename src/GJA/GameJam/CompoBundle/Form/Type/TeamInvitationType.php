<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamInvitationType extends AbstractType
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var User
     */
    protected $sender;

    public function __construct($type, User $sender)
    {
        $this->type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('team')
            ->add('type', 'hidden', $this->type)
            ->add('sender', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\TeamInvitation'
        ]);
    }

    public function getName()
    {
        return 'gamejam_compo_team_invitation';
    }
} 