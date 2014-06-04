<?php

namespace GJA\GameJam\UserBundle\Security;

use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LegacyPasswordEncoder implements PasswordEncoderInterface
{
    public function encodePassword($raw, $salt)
    {
        throw new \InvalidArgumentException("You should not encode password with this shit!");
    }

    public function isPasswordValid($encoded, $raw, $salt)
    {
        return $encoded === md5(md5($raw));
    }
} 