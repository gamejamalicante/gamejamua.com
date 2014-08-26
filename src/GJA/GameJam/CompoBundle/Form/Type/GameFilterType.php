<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\CompoBundle\Form\Model\GameFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('filterType', 'choice', ['choices' => GameFilter::getAvailableFilterTypes()])
            ->add('compo', 'entity', ['class' => 'GameJamCompoBundle:Compo', 'required' => false, 'empty_value' => 'Todas las ediciones'])
            ->add('diversifier', 'entity', ['class' => 'GameJamCompoBundle:Diversifier', 'required' => false, 'empty_value' => 'Todos los diversificadores'])
            ->add('order', 'choice', ['choices' => GameFilter::getAvailableOrder()]);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GJA\GameJam\CompoBundle\Form\Model\GameFilter'
        ));
    }

    public function getName()
    {
        return 'gamejam_compo_game';
    }
}
