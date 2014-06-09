<?php

namespace GJA\GameJam\CompoBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

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
        if(empty($value))
            return null;

        if($value instanceof User)
        {
            return $value->getUsername();
        }

        return null;
    }

    public function reverseTransform($value)
    {
        if(empty($value))
            return '';

        $user = $this->entityManager->getRepository("GameJamUserBundle:User")->findOneByUsernameOrEmail($value);

        return $user;
    }
} 