<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findStaff()
    {
        return $this->findByRole("ROLE_STAFF");
    }

    public function findOldStaff()
    {
        return $this->findByRole("ROLE_OLD_STAFF");
    }

    public function findByRole($role)
    {
        $dql = <<<DQL
SELECT u FROM GameJamUserBundle:User u WHERE u.roles LIKE ':role'
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('role', '%' . $role. '%')
            ->getResult();
    }
} 