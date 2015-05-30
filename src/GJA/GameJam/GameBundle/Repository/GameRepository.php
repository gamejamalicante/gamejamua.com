<?php

namespace GJA\GameJam\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Form\Model\GameFilter;
use GJA\GameJam\UserBundle\Entity\User;

class GameRepository extends EntityRepository
{
    public function findByFilter(GameFilter $filter)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        if ($filter->getFilterType() == GameFilter::FILTER_WINNER) {
            $queryBuilder->andWhere('g.winner IS NOT NULL')
                ->addOrderBy('g.winner', 'ASC');
        }

        if ($filter->getFilterType() == GameFilter::FILTER_OUT_OF_COMPO) {
            $queryBuilder->andWhere('g.compo IS NULL');
        }

        if ($compo = $filter->getCompo()) {
            $queryBuilder->andWhere('g.compo = :compo')
                ->setParameter('compo', $compo);
        }

        if ($diversifier = $filter->getDiversifier()) {
            $queryBuilder->join('g.diversifiers', 'diversifiers')
                ->andWhere('diversifiers IN(:diversifier)')
                ->setParameter('diversifier', $diversifier);
        }

        if ($filter->getOrder() == 'alpha') {
            $queryBuilder->addOrderBy('g.name', 'ASC');
        }

        if ($filter->getOrder() == 'likes') {
            $queryBuilder->addOrderBy('g.likes', 'DESC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    public function findByCompoAndNotVotedBy(User $user, Compo $compo, $ignoreList = array())
    {
        $games = $this->findByCompo($compo);

        foreach ($games as $game) {
            if ($ignoreList && in_array($game->getId(), $ignoreList)) {
                continue;
            }

            if (!$game->getScoreboardByVoter($user)) {
                return $game;
            }
        }

        return;
    }

    public function findTotalByVotingStatus($voted, User $user, Compo $compo)
    {
        $games = $this->findByCompo($compo);
        $total = 0;

        foreach ($games as $game) {
            if ($voted) {
                if ($game->getScoreboardByVoter($user)) {
                    $total++;
                }
            } else {
                $total++;
            }
        }

        return $total;
    }

    public function findVotedByUser(User $user, Compo $compo)
    {
        $dql = <<<DQL
SELECT g FROM GameJamGameBundle:Game g JOIN g.scoreboard sb WHERE g.compo = :compo AND sb.voter = :user AND sb.id IS NOT NULL
DQL;

        $result = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $user)
            ->setParameter('compo', $compo)
            ->getResult();

        return $result;
    }
}
