<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Security;

use FOS\UserBundle\Doctrine\UserManager;
use Jmikola\AutoLogin\User\AutoLoginUserProviderInterface;

class AutoLoginUserManager extends UserManager
    implements AutoLoginUserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByAutoLoginToken($key)
    {
        if (empty($key)) {
            throw new \Jmikola\AutoLogin\Exception\AutoLoginTokenNotFoundException();
        }

        $user = $this->findUserBy(array('autologinToken' => $key));

        if (!$user) {
            throw new \Jmikola\AutoLogin\Exception\AutoLoginTokenNotFoundException();
        }

        return $user;
    }
}
