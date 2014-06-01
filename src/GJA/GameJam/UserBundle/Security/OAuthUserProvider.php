<?php

namespace GJA\GameJam\UserBundle\Security;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;
use GJA\GameJam\UserBundle\Entity\User;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseUserProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\UserInterface;

class OAuthUserProvider extends BaseUserProvider
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(UserManagerInterface $userManager, array $properties, EntityManager $entityManager)
    {
        parent::__construct($userManager, $properties);

        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();
        $username = $this->getUserIdFromResponse($response, $service);

        $this->setUserServiceParameters($user, $username, $response->getAccessToken(), $service);

        $this->userManager->updateUser($user);
    }

    private function findUser(UserResponseInterface $response)
    {
        $service = $response->getResourceOwner()->getName();
        $username = $this->getUserIdFromResponse($response, $service);

        $user = $this->userManager->findUserByUsernameOrEmail($username);

        if(!is_null($user))
        {
            $this->setUserServiceParameters($user, $username, $response->getAccessToken(), $service);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->findUser($response);

        if (is_null($user)) {
            $service = $response->getResourceOwner()->getName();
            $username = $this->getUserIdFromResponse($response, $service);
            $user = $this->userManager->createUser();

            $user->setUsername($username);

            if($email = $response->getEmail())
                $user->setEmail($email);
            else
                $user->setEmail("nomail-YnnjNn7i0IO6V5BAnPUh@gamejamua.com"); // TODO: put this in config

            $user->setPassword(null);
            $user->setEnabled(true);

            $this->setUserServiceParameters($user, $username, $response->getAccessToken(), $service);
        }

        $this->userManager->updateUser($user);

        return $user;
    }

    private function setUserServiceParameters(User $user, $username, $accessToken, $service)
    {
        $user->setOAuthAccountUserId($service, $username);
        $user->setOAuthAccountAccessToken($service, $accessToken);
    }

    private function getUserIdFromResponse(UserResponseInterface $response, $service)
    {
        return $response->getEmail() ?: $response->getNickname();
    }
}