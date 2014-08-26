<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\CompoBundle\Form\DataTransformer\TeamInvitationTargetTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamInvitationType extends AbstractType
{
    /**
     * @var DataTransformerInterface
     */
    protected $userDataTransformer;

    public function __construct(EntityManager $entityManager)
    {
        $this->userDataTransformer = new TeamInvitationTargetTransformer($entityManager);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('type', 'hidden', ['data' => TeamInvitation::TYPE_INVITATION])
            ->add($builder->create('target', 'text', ['required' => true])->addModelTransformer($this->userDataTransformer));
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
