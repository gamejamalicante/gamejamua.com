<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateAutologinTokenCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->entityManager = $container->get("doctrine.orm.entity_manager");
    }

    protected function configure()
    {
        $this->setName("gamejam:compo:generate_autologin_tokens")
            ->setDescription("Generates autologin tokens for all users");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User[] $users */
        $users = $this->entityManager->getRepository('GameJamUserBundle:User')->findAll();

        foreach($users as $user)
        {
            $output->writeln('Creating new token for user: <info>' .$user->getUsername(). '</info>');
            $user->setAutologinToken(sha1($user->getId() . microtime() . uniqid()));
            $this->entityManager->persist($user);
            usleep(500);
        }

        $this->entityManager->flush();
    }
} 