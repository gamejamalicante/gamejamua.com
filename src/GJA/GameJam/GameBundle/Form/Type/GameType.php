<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('image', null, ['required' => false])
            ->add('diversifiers', null, ['required' => false, 'multiple' => true, 'expanded' => false])
            ->add('description')
            ->add('media', 'collection', [
                'type' => 'gamejam_game_media',
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ])
            ->add('downloads', 'collection', [
                'type' => 'gamejam_game_download',
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false
            ]);
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_game';
    }
} 