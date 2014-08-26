<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\TeamInvitation;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamRequestType extends AbstractTeamInvitationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('team', null, ['required' => true, 'query_builder' => function (EntityRepository $repository) {
                // TODO: move this to repository

                $builder = $repository->createQueryBuilder("t");

                $builder
                    ->andWhere("t.compo = :compo")
                    ->setParameter("compo", $this->compo);

                // TODO: add Having to limit per max user team members
                return $builder;
            }])
            ->add('type', 'hidden', ['data' => TeamInvitation::TYPE_REQUEST]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\TeamInvitation'
        ]);
    }

    public function getName()
    {
        return 'gamejam_compo_team_request';
    }
}
