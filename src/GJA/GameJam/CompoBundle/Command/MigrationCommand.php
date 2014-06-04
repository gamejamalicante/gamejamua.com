<?php

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Theme;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MigrationCommand extends ContainerAwareCommand
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var boolean
     */
    protected $dryRun;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->connection = $container->get("doctrine.dbal.legacy_connection");
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }

    protected function configure()
    {
        $this->setName("gamejam:migration:migrate")
            ->addOption("dry-run", "dr", InputOption::VALUE_NONE, "Dry run the process");
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->dryRun = true;
        $this->output = $output;

        // migrate editions
        $this->migrateEditions();

        // migrate users
        $this->migrateUsers();

        // migrate posts

        // migrate games

        // migrate game media

        // migrate likes / votes

        // migrate applications

        // migrate diversifiers

        // migrate themes
    }

    protected function migrateEditions()
    {
        $editions = $this->connection->query("SELECT * FROM EDICIONES;")->fetchAll();

        foreach($editions as $edition)
        {
            $edition = (object) $edition;
            $this->output->writeln("Migrating compo: <info>" . $edition->titulo. "</info>");

            $compo = new Compo();
            $compo->setName($edition->titulo);
            $compo->setStartAt(new \DateTime($edition->fecha));
            $compo->setOpen(false);
            $compo->setPeriod("P2D");
            $compo->setMaxPeople(35);
            $compo->setTheme($this->findTheme($edition->tema));

            $this->persist($compo);
        }

        $this->flush();
    }

    protected function migrateUsers()
    {
        $users = $this->connection->query("SELECT * FROM USUARIOS u JOIN DATOSUSUARIO d ON d.id = u.id;")->fetchAll();

        foreach($users as $user)
        {
            $user = (object) $user;

            $this->output->writeln("Migrating user: <info>" . $user->nombre . "</info>");

            $userEntity = new User();
            $userEntity->setUsername($user->nombre);
            $userEntity->setEmail($user->email);
            $userEntity->setNickname($user->nick);
            $userEntity->setLegacyPassword(true);
            $userEntity->setPassword($user->password);
            $userEntity->setRoles(array("ROLE_USER", "ROLE_OLD"));
            $userEntity->setCoins($user->puntos);
            $userEntity->setTwitter($user->twitter);
            $userEntity->setBirthDate(new \DateTime($user->cumple));
            $userEntity->setRegisteredAt(new \DateTime($user->fecha));
            $userEntity->setPresentation($user->descripcion);
            $userEntity->setAvatarUrl($user->imagen);
            $userEntity->setCity($user->localizacion);
            $userEntity->setSiteUrl($user->paginaweb);
            $userEntity->setEnabled(true);
            $userEntity->setPublicEmail($user->emailpublico);
            $userEntity->setPublicProfile($user->datospublicos);

            $this->persist($userEntity);
        }

        $this->flush();
    }

    protected function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    protected function flush($entity = null)
    {
        if(!$this->dryRun)
            $this->entityManager->flush($entity);
    }

    protected function persistAndFlush($entity)
    {
        $this->persist($entity);
        $this->flush($entity);
    }

    protected function persistAndFlushAll($entity)
    {
        $this->persist($entity);
        $this->flush();
    }

    protected function find($repository, $field, $value)
    {
        return $this->entityManager->getRepository($repository)->findOneBy([$field => $value]);
    }

    protected function findTheme($theme)
    {
        if($themeEntity = $this->find("GameJamCompoBundle:Theme", 'name', $theme))
            return $themeEntity;

        $themeEntity = new Theme();
        $themeEntity->setName($theme);

        return $themeEntity;
    }
} 