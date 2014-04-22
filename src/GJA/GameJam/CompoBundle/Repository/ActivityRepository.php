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
use GJA\GameJam\CompoBundle\Entity\Compo;

class ActivityRepository extends EntityRepository
{
    public function findAllSince(\DateTime $since, Compo $compo = null)
    {
        $query = $this->createQueryBuilder("a")
            ->andWhere("a.date >= :since")
            ->orderBy("a.date", "DESC")
            ->setMaxResults(30)
            ->setParameter('since', $since);

        if($compo)
            $query->andWhere("a.compo = :compo")
                ->setParameter('compo', $compo);

        return $query->getQuery()->getResult();
    }
} 