<?php

namespace GJA\GameJam\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Form\Model\GameFilter;

class GameRepository extends EntityRepository
{
    public function findByFilter(GameFilter $filter)
    {
        $queryBuilder = $this->createQueryBuilder("g");

        if($compo = $filter->getCompo())
            $queryBuilder->where('g.compo = :compo')->setParameter('compo', $compo);

        if($diversifier = $filter->getDiversifier())
            $queryBuilder->join('g.diversifiers', 'diversifiers')
                ->where('diversifiers IN(:diversifier)')->setParameter('diversifier', $diversifier);

        if($filter->getOrder() == 'alpha')
            $queryBuilder->addOrderBy('g.name', 'ASC');

        if($filter->getOrder() == 'likes')
            $queryBuilder->addOrderBy('g.likes', 'DESC');

        return $queryBuilder->getQuery()->getResult();
    }
} 