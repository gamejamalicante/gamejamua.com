<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Order;

interface ItemInterface
{
    public function getAmount();
    public function getDescription();
    public function getQuantity();
} 