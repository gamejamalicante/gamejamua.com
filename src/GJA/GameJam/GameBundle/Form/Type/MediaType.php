<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use GJA\GameJam\GameBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', null, ['required' => false])
            ->add('type', 'choice', ['choices' => Media::getAvailableTypes()])
            ->add('comment');
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_media';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\GameBundle\Entity\Media'
        ]);
    }
} 