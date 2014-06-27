<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use GJA\GameJam\GameBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Game $game */
        $game = $options['data'];

        $builder->add('name')
            ->add('image', 'thrace_image_upload', array(
                'label' => 'form.image',
                'data_class' => 'GJA\GameJam\GameBundle\Entity\Media',
                'configs' => array(
                    'minWidth' => 370,
                    'minHeight' => 110,
                    'extensions' => 'jpeg,jpg,png',
                    'max_upload_size' => '400000',
                    'view_button'    => false,
                    'meta_button'    => false,
                    'rotate_button'  => false,
                    'reset_button'   => false,
                ),
            ))
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