<?php

namespace GJA\GameJam\CompoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use GJA\GameJam\UserBundle\Entity\User;

class TeamInvitationRepository extends EntityRepository
{
    public function findAllByUser(User $user)
    {
        $dql = <<<DQL
SELECT t FROM GameJamCompoBundle:TeamInvitation t WHERE t.sender = :user OR t.target = :user
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $user)
            ->getResult();
    }
} 