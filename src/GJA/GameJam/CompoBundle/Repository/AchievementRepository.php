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