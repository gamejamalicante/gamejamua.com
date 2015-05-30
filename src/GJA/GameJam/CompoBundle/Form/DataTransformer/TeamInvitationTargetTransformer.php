<?php

namespace GJA\GameJam\CompoBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;

class TeamInvitationTargetTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($value)
    {
        if (empty($value)) {
            return;
        }

        if ($value instanceof User) {
            return $value->getUsername();
        }

        return;
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return '';
        }

        $user = $this->entityManager->getRepository('GameJamUserBundle:User')->findOneByUsernameOrEmail($value);

        return $user;
    }
}
