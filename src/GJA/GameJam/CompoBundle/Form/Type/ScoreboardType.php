<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ScoreboardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('graphics', 'choice', $this->getVoteOptions())
            ->add('audio', 'choice', $this->getVoteOptions())
            ->add('originality', 'choice', $this->getVoteOptions())
            ->add('fun', 'choice', $this->getVoteOptions())
            ->add('theme', 'choice', $this->getVoteOptions())
            ->add('comment', null, array('required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'GJA\GameJam\CompoBundle\Entity\Scoreboard'
        ));
    }

    public function getName()
    {
        return 'gamejam_compo_scoreboard';
    }

    protected function getVoteOptions()
    {
        return array(
            'choices' => array(
                '1' => '1 - Mal, necesita más trabajo',
                '2' => '2 - Mejorable',
                '3' => '3 - Regular',
                '4' => '4 - Bien',
                '5' => '5 - Genial, ¡buen trabajo!'
            ),
            'data' => 3
        );
    }
}
