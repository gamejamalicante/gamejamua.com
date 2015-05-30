<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Order;

use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\UserBundle\Entity\User;

class CompoInscriptionItem implements ItemInterface
{
    /**
     * @var Compo
     */
    protected $compo;

    /**
     * @var User
     */
    protected $user;

    public function __construct(Compo $compo, User $user)
    {
        $this->compo = $compo;
        $this->user = $user;
    }

    public function getAmount()
    {
        if ($this->user->isMember()) {
            return $this->compo->getMemberFee();
        }

        return $this->compo->getNormalFee();
    }

    public function getDescription()
    {
        return 'Inscripción '.$this->compo->getName();
    }

    public function getQuantity()
    {
        return 1;
    }
}
