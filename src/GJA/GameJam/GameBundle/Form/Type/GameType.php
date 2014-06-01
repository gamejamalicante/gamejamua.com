<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('image')
            ->add('diversifiers')
            ->add('description')
            ->add('media', 'gamejam_game_media')
            ->add('downloads', 'gamejam_game_download');
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_game';
    }
} 