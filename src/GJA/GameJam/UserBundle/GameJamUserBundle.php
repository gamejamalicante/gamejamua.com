<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class GameJamUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
