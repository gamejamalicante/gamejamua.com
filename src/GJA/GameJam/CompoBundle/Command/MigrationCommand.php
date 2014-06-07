<?php

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\Activity;
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

    /**
     * @var Compo[]
     */
    protected $edicionesMap = array();

    /**
     * @var User[]
     */
    protected $usuariosMap = array();

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->connection = $container->get("doctrine.dbal.legacy_connection");
        $this->entityManager = $container->get('doctrine.orm.entity_manager');
    }

    protected function configure()
    {
        $this->setName("gamejam:migration:migrate")
            ->setDescription("Migrate old database data to new system")
            ->addOption("dry-run", "dr", InputOption::VALUE_NONE, "Dry run the process");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dryRun = $input->getOption("dry-run");
        $this->output = $output;

        // migrate editions
        $this->migrateEditions();

        // migrate users
        $this->migrateUsers();

        // migrate posts
        $this->migratePosts();

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

            $this->edicionesMap[$edition->id] = $compo;
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

            if($user->cumple == '0000-00-00')
                $user->cumple = null;

            if($user->sexo == '0')
                $user->sexo = null;
            elseif($user->sexo == '1')
                $user->sexo = 0;
            elseif($user->sexo == '2')
                $user->sexo = 1;
            else
                $user->sexo = null;

            $userEntity = new User();
            $userEntity->setUsername($user->nombre);
            $userEntity->setEmail($user->email);
            $userEntity->setNickname($user->nick);
            $userEntity->setLegacyPassword(true);
            $userEntity->setPassword($user->password);
            $userEntity->setRoles(array("ROLE_USER", "ROLE_OLD"));
            $userEntity->setCoins($user->puntos);
            $userEntity->setTwitter($user->twitter);
            $userEntity->setBirthDate($user->cumple ? new \DateTime($user->cumple) : null);
            $userEntity->setRegisteredAt(new \DateTime($user->fecha));
            $userEntity->setPresentation($user->descripcion);
            $userEntity->setAvatarUrl($user->imagen);
            $userEntity->setCity($user->localizacion);
            $userEntity->setSiteUrl($user->paginaweb);
            $userEntity->setEnabled(true);
            $userEntity->setPublicEmail($user->emailpublico);
            $userEntity->setPublicProfile($user->datospublicos);
            $userEntity->setSex($user->sexo);

            $this->persist($userEntity);

            $this->usuariosMap[$user->id] = $userEntity;
        }

        $this->flush();
    }

    protected function migratePosts()
    {
        $posts = $this->connection->query("SELECT * FROM POSTS;")->fetchAll();

        foreach($posts as $post)
        {
            $post = (object) $post;

            $date = new \DateTime($post->fecha);
            $compo = $this->findGameJamInDateRange($date);

            if($compo)
            {
                $this->output->writeln("\tFound gamejam for post: <info>" .$compo. "</info>");
            }

            $activity = new Activity();
            $activity->setType(Activity::TYPE_SHOUT);
            $activity->setDate($date);
            $activity->setContent(['content' => $post->contenido]);
            $activity->setUser($this->usuariosMap[$post->usuario]);
            $activity->setCompo($compo);

            $this->output->writeln("Migrating post: <info>" .strlen($post->contenido). "</info> characters found in GameJam: <info>" .$compo. "</info>");

            $this->persist($activity);
        }

        $this->flush();
    }

    protected function findGameJamInDateRange(\DateTime $date)
    {
        /** @var Compo[] $compos */
        $compos = $this->entityManager->getRepository("GameJamCompoBundle:Compo")->findAll();

        foreach($compos as $compo)
        {
            if($compo->getStartAt() <= $date and $compo->getEndAt() >= $date)
                return $compo;
        }

        return null;
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