<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamInvitationType extends AbstractTeamInvitationType
{
    /**
     * @var \GJA\GameJam\UserBundle\Entity\User
     */
    protected $user;

    public function __construct(Compo $compo, User $user)
    {
        parent::__construct($compo);

        $this->user = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('type', 'hidden', ['data' => TeamInvitation::TYPE_INVITATION])
            ->add('target', null, ['required' => true, 'query_builder' => function(EntityRepository $repository)
                {
                    $builder = $repository->createQueryBuilder("u");

                    $builder->andWhere("u != :user")
                        ->setParameter('user', $this->user);

                    return $builder;
                }]);
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