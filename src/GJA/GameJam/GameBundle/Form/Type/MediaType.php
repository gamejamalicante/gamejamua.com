<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use GJA\GameJam\GameBundle\Entity\Media;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Thrace\MediaBundle\Form\Type\ImageUploadType;

class MediaType extends ImageUploadType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', null, ['required' => false])
            ->add('type', 'choice', ['choices' => Media::getAvailableTypes()])
            ->add('comment');
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_media';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'label' => 'form.image',
            'data_class' => 'GJA\GameJam\GameBundle\Entity\Media',
            'configs' => array(
                'minWidth' => 100,
                'minHeight' => 100,
                'extensions' => 'jpeg,jpg,png',
                'view_button' => false,
                'meta_button' => false,
                'rotate_button' => false,
                'reset_button' => false,
                'enabled_button' => false,
                'delete_button' => false,
            ),
        ]);
    }

    public function getParent()
    {
        return 'thrace_image_upload';
    }
}
