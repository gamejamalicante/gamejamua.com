<?php

namespace GJA\GameJam\CompoBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ContributorRepository extends EntityRepository
{
    public function findSponsors()
    {
        $dql = <<<DQL
SELECT c FROM GameJamCompoBundle:Contributor c JOIN c.composSponsored cs ORDER BY c.featured DESC, c.name ASC
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->getResult();
    }

    public function findJuries()
    {
        $dql = <<<DQL
SELECT c FROM GameJamCompoBundle:Contributor c JOIN c.composJudged cs
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->getResult();
    }
}
