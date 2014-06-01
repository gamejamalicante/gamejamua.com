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
use Doctrine\ORM\NoResultException;
use GJA\GameJam\CompoBundle\Entity\Activity;
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

    public function findOnlyActivity($limit = 10)
    {
        $query = $this->createQueryBuilder("a")
            ->andWhere("a.type IN (:types)")
            ->setParameter('types', [
                Activity::TYPE_ACHIEVEMENT,
                Activity::TYPE_COINS,
                Activity::TYPE_CREATION,
                Activity::TYPE_INFO_UPDATE,
                Activity::TYPE_LIKES,
                Activity::TYPE_LIKES,
                Activity::TYPE_MEDIA
            ])
        ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function findOnlyMessages($limit = 10)
    {
        $query = $this->createQueryBuilder("a")
            ->andWhere("a.type IN (:types)")
            ->andWhere("a.user IS NOT NULL")
            ->setParameter('types', [
                Activity::TYPE_SHOUT,
                Activity::TYPE_TWITTER
            ])
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function findTwitterMentions($limit = 10)
    {
        $query = $this->createQueryBuilder("a")
            ->andWhere("a.type = :type")
            ->andWhere("a.user IS NULL")
            ->setParameter('type', Activity::TYPE_TWITTER)
            ->setMaxResults($limit);

        return $query->getQuery()->getResult();
    }

    public function findLastTwitterInteraction()
    {
        return $this->findOneBy(['type' => Activity::TYPE_TWITTER], ['date' => "ASC"]);
    }
} 