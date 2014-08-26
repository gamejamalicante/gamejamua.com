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

class ProMembershipItem implements ItemInterface
{
    const FEE = 10.00;

    public function getAmount()
    {
        return self::FEE;
    }

    public function getDescription()
    {
        return 'Socio de pleno derecho GameJam Alicante';
    }

    public function getQuantity()
    {
        return 1;
    }
}
