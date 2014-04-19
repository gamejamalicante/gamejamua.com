<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class AchievementRepository extends EntityRepository
{
    public function findGranters()
    {
        $dql = <<<DQL
SELECT a FROM GameJamCompoBundle:Achievement a WHERE a.granter IS NOT NULL
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->getResult();
    }
} 