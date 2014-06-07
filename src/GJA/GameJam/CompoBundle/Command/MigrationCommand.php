<?php

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use GJA\GameJam\CompoBundle\Entity\Achievement;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\CompoApplication;
use GJA\GameJam\CompoBundle\Entity\Diversifier;
use GJA\GameJam\CompoBundle\Entity\Theme;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\GameBundle\Entity\Media;
use GJA\GameJam\UserBundle\Entity\AchievementGranted;
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

    /**
     * @var Game[]
     */
    protected $juegosMap = array();

    /**
     * @var Diversifier[]
     */
    protected $diversificadoresMap = array();

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

        // migrate diversifiers
        $this->migrateDiversifiers();

        // migrate games
        $this->migrateGames();

        // migrate game media
        $this->migrateGameMedia();

        // migrate applications
        $this->migrateApplications();

        // migrate achievements
        $this->migrateAchievements();
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

    protected function migrateDiversifiers()
    {
        $diversifiers = $this->connection->query("SELECT * FROM DIVERSIFICADORES")->fetchAll();

        $duplicatedDiversifiers = array();

        foreach($diversifiers as $diversifier)
        {
            $diversifier = (object) $diversifier;

            if(in_array($diversifier->diversificador, array_keys($duplicatedDiversifiers)))
                continue;

            $this->output->writeln("Migrating diversifier: <info>" .$diversifier->diversificador. "</info>");

            $diversifierEntity = new Diversifier();
            $diversifierEntity->setName($diversifier->diversificador);

            $this->persist($diversifierEntity);

            $this->diversificadoresMap[$diversifier->id] = $diversifierEntity;
            $duplicatedDiversifiers[$diversifier->diversificador] = true;
        }

        $this->flush();
    }

    protected function migrateGames()
    {
        $games = $this->connection->query("SELECT * FROM JUEGOS;")->fetchAll();

        foreach($games as $game)
        {
            $game = (object) $game;

            if(empty($game->titulo))
                continue;

            $this->output->writeln("Migrating game: <info>" .$game->titulo. "</info>");

            $compo = @$this->edicionesMap[$game->edicion];

            $gameEntity = new Game();
            $gameEntity->setCompo($compo);
            $gameEntity->setName($game->titulo);
            $gameEntity->setImage($game->imagen);
            $gameEntity->setDescription($game->descripcion);
            $gameEntity->setLikes($game->megusta);
            $gameEntity->setCreatedAt($compo ? $compo->getStartAt() : new \DateTime("now"));

            // diversifiers
            $diversifiers = $this->connection->query("SELECT * FROM MISDIVERSIFICADORES WHERE juego = " .$game->id);

            foreach($diversifiers as $diversifier)
            {
                $diversifier = (object) $diversifier;

                $gameEntity->addDiversifier($this->diversificadoresMap[$diversifier->diversificador]);
            }

            $this->persist($gameEntity);

            $this->juegosMap[$game->id] = $gameEntity;
        }

        $this->flush();
    }

    protected function migrateGameMedia()
    {
        $gameMedia = $this->connection->query("SELECT * FROM MEDIAJUEGOS")->fetchAll();

        foreach($gameMedia as $media)
        {
            $media = (object) $media;

            $this->output->writeln("Migrating game media: <info>" .$media->url. "</info>");

            $game = @$this->juegosMap[$media->juego];

            if(!$game)
                continue;

            $type = Media::TYPE_IMAGE;

            if(preg_match("/youtube/i", $media->url))
                $type = Media::TYPE_VIDEO;

            if(preg_match("/lapse/i", $media->comentario))
                $type = Media::TYPE_TIMELAPSE;

            $mediaEntity = new Media();
            $mediaEntity->setType($type);
            $mediaEntity->setComment($media->comentario);
            $mediaEntity->setCreatedAt($game->getCreatedAt());
            $mediaEntity->setUrl($media->url);
            $mediaEntity->setGame($game);

            $this->persist($mediaEntity);
        }

        $this->flush();
    }

    protected function migrateApplications()
    {
        $applications = $this->connection->query("SELECT p.*, pn.usuario AS pasanoche FROM PARTICIPANTES p LEFT JOIN PASANLANOCHE pn ON pn.usuario = p.usuario");

        foreach($applications as $application)
        {
            $application = (object) $application;

            $this->output->writeln("Migrating application from user: <info>" .$application->usuario. "</info> for compo: <info>" .$application->edicion. "</info>");

            if($application->edicion == '0')
                continue;

            $user = @$this->usuariosMap[$application->usuario];

            if(!$user)
                continue;

            $compo = $this->edicionesMap[$application->edicion];
            $game = @$this->juegosMap[$application->juego];

            if(!$game)
                continue;

            $modality = CompoApplication::MODALITY_NORMAL;

            if($application->categoria == '1')
                $modality = CompoApplication::MODALITY_OUT_OF_COMPO;

            if($application->categoria == '2')
                $modality = CompoApplication::MODALITY_FREE;

            $compoApplication = new CompoApplication();
            $compoApplication->setModality($modality);
            $compoApplication->setUser($user);
            $compoApplication->setCompo($compo);
            $compoApplication->setDate($compo->getStartAt());
            $compoApplication->setCompleted(true);
            $compoApplication->setNightStay($application->pasanoche ? true : false);

            $this->persist($compoApplication);
        }

        $this->flush();
    }

    protected function migrateAchievements()
    {
        $achievements = $this->connection->query("SELECT * FROM LOGROS");

        foreach($achievements as $achievement)
        {
            $achievement = (object) $achievement;

            $this->output->writeln("Migrating achievement: <info>" .$achievement->nombre. "</info>");

            $type = Achievement::TYPE_GAME;

            if($achievement->categoria == '2')
                $type = Achievement::TYPE_USER;

            $achievementEntity = new Achievement();
            $achievementEntity->setName($achievement->nombre);
            $achievementEntity->setDescription($achievement->descripcion);
            $achievementEntity->setType($type);
            $achievementEntity->setHidden($achievement->oculto);

            // try to find granted date
            preg_match("/([0-9]+)\/([0-9]+)\/([0-9]+)/i", $achievement->nombre, $matches);

            if(@$matches[0])
            {
                $day = $matches[1];
                $month = $matches[2];
                $year = $matches[3];

                $date = new \DateTime("now");
                $date->setDate($year, $month, $day);
                $date->setTime(0, 0, 0);
            }
            else
            {
                $date = null;
            }

            // granted data
            $granted = $this->connection->query("SELECT * FROM PREMIADOS WHERE logro = " .$achievement->id);

            foreach($granted as $grant)
            {
                $grant = (object) $grant;

                $this->output->writeln("\tMigrating achievement grant for user: <info>" .$grant->usuario. "</info>");

                $achievementGranted = new AchievementGranted();
                $achievementGranted->setUser($this->usuariosMap[$grant->usuario]);
                $achievementGranted->setGame(@$this->juegosMap[$grant->juego]);
                $achievementGranted->setAchievement($achievementEntity);
                $achievementGranted->setGrantedAt($date);

                $this->persist($achievementGranted);
            }
        }

        $this->flush();
    }

    protected function findGameJamInDateRange(\DateTime $date)
    {
        /** @var Compo[] $compos */
        $compos = $this->entityManager->getRepository("GameJamCompoBundle:Compo")->findAll();

        foreach($compos as $compo)
        {
            if($date >= $compo->getStartAt() and $date <= $compo->getEndAt())
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