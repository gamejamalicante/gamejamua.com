<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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