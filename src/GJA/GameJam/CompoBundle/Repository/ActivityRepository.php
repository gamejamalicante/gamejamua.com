<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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