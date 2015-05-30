<?php

namespace GJA\GameJam\GameBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GiveCoinsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('coins', 'choice', ['choices' => ['1' => '1 UltraCoin', '3' => '3 UltraCoin', '5' => '5 UltraCoin']]);
    }

    public function getName()
    {
        return 'gamejam_game_give_coins';
    }
}
