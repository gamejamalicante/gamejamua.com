<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use GJA\GameJam\CompoBundle\Entity\Compo;

class CompoRepository extends EntityRepository
{
    public function findRunningCompo()
    {
        $dql = <<<DQL
SELECT c FROM GameJamCompoBundle:Compo c WHERE c.open = 1 AND c.startAt <= :date
DQL;

        try {
            $result = $this->getEntityManager()->createQuery($dql)
                ->setParameter('date', new \DateTime('now'))
                ->getSingleResult();
        } catch (NonUniqueResultException $ex) {
            return;
        } catch (NoResultException $ex) {
            return;
        }

        return $result;
    }

    public function findLastCompo()
    {
        $dql = <<<DQL
SELECT c FROM GameJamCompoBundle:Compo c WHERE c.applicationEndAt < :date
DQL;

        try {
            return $this->getEntityManager()->createQuery($dql)
                ->setParameter('date', new \DateTime("now"))
                ->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    public function findJoinedMembers(Compo $compo)
    {
        $dql = <<<DQL
SELECT u FROM GameJamUserBundle:User u JOIN u.applications ap WHERE ap.completed = true AND ap.compo = :compo
DQL;

        $result = $this->getEntityManager()->createQuery($dql)
            ->setParameter('compo', $compo)
            ->getArrayResult();

        return $result;
    }
}
