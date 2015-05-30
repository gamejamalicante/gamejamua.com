<?php

namespace GJA\GameJam\CompoBundle\Form\Type;

use GJA\GameJam\CompoBundle\Entity\Compo;
use Symfony\Component\Form\AbstractType;

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
