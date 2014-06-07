<?php

namespace GJA\GameJam\CompoBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use GJA\GameJam\UserBundle\Entity\User;

class NotificationRepository extends EntityRepository
{
    public function findTotalPendingByUser(User $user)
    {
        $dql = <<<DQL
SELECT COUNT(n.id) as total, COUNT(ur.id) as readed FROM GameJamCompoBundle:Notification n
    LEFT JOIN n.users us
    LEFT JOIN n.usersRead ur
    WHERE
        (n.type = 1
            OR
                (n.type = 2 AND us IN(:user))
            OR
                (n.type = 3 AND us NOT IN(:user))
        )
    AND n.announce = 1
DQL;

        $result = $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $user);

        try
        {
            $result = (object) $result->getSingleResult();

            return $result->total - $result->readed;
        }
        catch(NoResultException $ex)
        {
            return 0;
        }
    }

    public function findByUser(User $user = null)
    {
        $dql = <<<DQL
SELECT n FROM GameJamCompoBundle:Notification n
    LEFT JOIN n.users us
    LEFT JOIN n.usersRead ur
    WHERE
        (n.type = 1
            OR
                (n.type = 2 AND us IN(:user))
            OR
                (n.type = 3 AND us NOT IN(:user))
        )
    AND n.announce = 1
    ORDER BY ur.id ASC, n.date DESC
DQL;

        return $this->getEntityManager()->createQuery($dql)
            ->setParameter('user', $user)
            ->getResult();
    }
} 