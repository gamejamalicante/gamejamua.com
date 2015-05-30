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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\GameBundle\Entity\Game;

class ScoreboardRepository extends EntityRepository
{
    public function findByCompo(Compo $compo)
    {
        $query = <<<DQL
SELECT g FROM GameJamGameBundle:Game g JOIN g.scoreboard sb WHERE g.compo = :compo
DQL;

        /** @var ArrayCollection $ranking */
        $ranking = $this->getEntityManager()->createQuery($query)
            ->setParameter('compo', $compo)
            ->getResult();

        uasort($ranking, function (Game $left, Game $right) {
            return $this->getGameTotalPoints($left) < $this->getGameTotalPoints($right);
        });

        return $ranking;
    }

    private function getGameTotalPoints(Game $game)
    {
        $total = 0;

        foreach ($game->getScoreboard() as $scoreboard) {
            $total += $scoreboard->getTotal();
        }

        return $total;
    }
}
