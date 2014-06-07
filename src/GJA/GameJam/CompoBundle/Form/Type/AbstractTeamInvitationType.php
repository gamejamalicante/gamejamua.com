<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class AbstractTeamInvitationType extends AbstractType
{
    /**
     * @var \GJA\GameJam\CompoBundle\Entity\Compo
     */
    protected $compo;

    public function __construct(Compo $compo)
    {
        $this->compo = $compo;
    }
} 