<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use GJA\GameJam\GameBundle\Entity\Download;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DownloadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('version')
            ->add('fileUrl')
            ->add('comment')
            ->add('platforms', 'choice', ['choices' => Download::getAvailablePlatforms(), 'multiple' => true, 'expanded' => false])
            ->add('size', null, ['required' => false]);
    }

    /**
     * @{inheritDoc}
     */
    public function getName()
    {
        return 'gamejam_game_download';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'GJA\GameJam\GameBundle\Entity\Download'
        ]);
    }
}
