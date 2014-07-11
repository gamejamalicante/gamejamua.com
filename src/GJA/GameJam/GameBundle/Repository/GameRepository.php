<?php

namespace GJA\GameJam\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Form\Model\GameFilter;
use GJA\GameJam\GameBundle\Entity\Media;

class GameRepository extends EntityRepository
{
    public function findByFilter(GameFilter $filter)
    {
        $queryBuilder = $this->createQueryBuilder("g");

        if($filter->getFilterType() == GameFilter::FILTER_WINNER)
        {
            $queryBuilder->andWhere("g.winner IS NOT NULL")
                ->addOrderBy("g.winner", "ASC");
        }

        if($filter->getFilterType() == GameFilter::FILTER_OUT_OF_COMPO)
        {
            $queryBuilder->andWhere("g.compo IS NULL");
        }

        if($compo = $filter->getCompo())
        {
            $queryBuilder->andWhere('g.compo = :compo')
                ->setParameter('compo', $compo);
        }

        if($diversifier = $filter->getDiversifier())
        {
            $queryBuilder->join('g.diversifiers', 'diversifiers')
                ->andWhere('diversifiers IN(:diversifier)')
                ->setParameter('diversifier', $diversifier);
        }

        if($filter->getOrder() == 'alpha')
            $queryBuilder->addOrderBy('g.name', 'ASC');

        if($filter->getOrder() == 'likes')
            $queryBuilder->addOrderBy('g.likes', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
} 