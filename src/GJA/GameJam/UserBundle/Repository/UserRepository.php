<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

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
SELECT u FROM GameJamUserBundle:User u WHERE u.roles LIKE :role
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('role', '%' . $role. '%')
            ->getResult();
    }

    public function findOneByUsernameOrEmail($username)
    {
        $dql = <<<DQL
SELECT u FROM GameJamUserBundle:User u WHERE u.username = :username OR u.email = :username
DQL;

        try {
            return $this->getEntityManager()->createQuery($dql)
                ->setParameter('username', $username)
                ->getSingleResult();
        } catch (NoResultException $ex) {
            return null;
        }
    }
}
