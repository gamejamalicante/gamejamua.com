<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Order;

class MembershipItem implements ItemInterface
{
    const FEE = 3.00;

    public function getAmount()
    {
        return self::FEE;
    }

    public function getDescription()
    {
        return 'Socio GameJam Alicante';
    }

    public function getQuantity()
    {
        return 1;
    }
} 