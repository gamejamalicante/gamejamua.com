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

class CompoRepository extends EntityRepository
{
    public function findRunningCompo()
    {
        $dql = <<<DQL
SELECT c FROM GameJamCompoBundle:Compo c WHERE c.open = 1 AND c.startAt <= :date
DQL;

        try
        {
            $result = $this->getEntityManager()->createQuery($dql)
                ->setParameter('date', new \DateTime('now'))
                ->getSingleResult();
        } catch (NonUniqueResultException $ex) {
            return null;
        } catch (NoResultException $ex) {
            return null;
        }

        return $result;
    }
} 