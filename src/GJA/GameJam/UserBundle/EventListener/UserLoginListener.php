<?php



namespace GJA\GameJam\UserBundle\EventListener;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLoginListener
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();
        $request = $event->getRequest();

        $user->setLastIp($request->getClientIp());

        $this->entityManager->persist($user);
        $this->entityManager->flush($user);
    }
} 