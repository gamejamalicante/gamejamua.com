<?php

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\ORM\EntityManager;
use Endroid\Twitter\Twitter;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Repository\ActivityRepository;
use GJA\GameJam\CompoBundle\Service\LinkUnshortener;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class FetchTwitterActivityCommand extends ContainerAwareCommand
{
    protected $twitterMap = array();

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->entityManager = $container->get("doctrine.orm.entity_manager");
        $this->eventDispatcher = $container->get("event_dispatcher");
    }

    protected function configure()
    {
        $this->setName("gamejam:compo:fetch_twitter_activity")
            ->setDescription("Fetch twitter activity and generates events")
            ->addOption('dry-run', 'dr', InputOption::VALUE_NONE, 'Dry run the process');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dryRun = $input->getOption('dry-run');

        /** @var Twitter $twitter */
        $twitter = $this->getContainer()->get('endroid.twitter');

        $query = $this->prepareQuery($output);
        $sinceId = $this->getSinceId();

        $parameters = array(
            'q' => $query,
        );

        if($sinceId)
        {
            $output->writeln("Found last tweet ID: <info>" .$sinceId. "</info>");
            $parameters['since_id'] = $sinceId;
        }

        try
        {
            $response = $twitter->query("search/tweets", "get", "json", $parameters);
        }
        catch(\Exception $ex)
        {
            $output->writeln("Failed fetching Tweets: ". $ex->getMessage());
            return 0x255;
        }

        $response = json_decode($response->getContent());

        $tweets = $response->statuses;

        if(empty($tweets))
        {
            $output->writeln("No tweets fetched");
        }

        foreach($tweets as $tweet)
        {
            if(preg_match('/^RT.*/i', $tweet->text))
            {
                $output->writeln('Ignoring retweet');
                continue;
            }

            $user = $this->findUserFromTwitterUsername($tweet->user->screen_name);

            $activity = new Activity();
            $activity->setType(Activity::TYPE_TWITTER);
            $activity->setUser($user);
            $activity->setCompo($this->getCurrentCompo());
            $activity->setDate(new \DateTime($tweet->created_at));

            /** @var LinkUnshortener $linkUnshortener */
            $linkUnshortener = $this->getContainer()->get('gamejam.compo.link_unshortener');

            $content = $linkUnshortener->findAndUnshortenLinks($tweet->text);
            $content = $this->findAndLinkHastagsAndMentions($content);

            $content = array(
                'content' => $content,
                'id' => $tweet->id
            );

            if(!$user)
            {
                // random user, save avatar and screen name
                $content['avatar'] = $tweet->user->profile_image_url;
                $content['screen_name'] = $tweet->user->screen_name;
            }

            $activity->setContent($content);

            $output->writeln("Found tweet: <info>" .($activity->getUser() ?: 'Random user'). "</info>, with content: " .$activity->getContent()['content']);

            $this->entityManager->persist($activity);
        }

        if(!$dryRun)
            $this->entityManager->flush();
    }

    protected function findUserFromTwitterUsername($username)
    {
        if(isset($this->twitterMap[$username]))
            return $this->twitterMap[$username];

        $user = $this->entityManager->getRepository("GameJamUserBundle:User")->findOneByTwitter($username);

        if($user)
        {
            $this->twitterMap[$username] = $user;

            return $user;
        }

        return null;
    }

    protected function getCurrentCompo()
    {
        return $this->entityManager->getRepository("GameJamCompoBundle:Compo")->findOneBy(['open' => true], ['id' => 'ASC']);
    }

    protected function prepareQuery(OutputInterface $output)
    {
        $hashtags = $this->getContainer()->getParameter("twitter.hashtags");
        $username = "@" . $this->getContainer()->getParameter("twitter.username");

        foreach($hashtags as $key => $hashtag)
        {
            $hashtags[$key] = "#" . $hashtag;
        }

        $hashtags[] = $username;

        $query = implode($hashtags, " OR ");

        $output->writeln('Query to execute: <info>' .$query. '</info>');

        return $query;
    }

    protected function getSinceId()
    {
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $this->entityManager->getRepository("GameJamCompoBundle:Activity");

        /** @var Activity $lastInteraction */
        $lastInteraction = $activityRepository->findLastTwitterInteraction();

        if($lastInteraction)
        {
            if(isset($lastInteraction->getContent()['id']))
                return $lastInteraction->getContent()['id'];
        }

        return null;
    }

    private function findAndLinkHastagsAndMentions($content)
    {
        preg_match_all('/(?<mention>(?:#|@)(?:.*?)(?:$| ))/i', $content, $matches);

        foreach($matches['mention'] as $mention)
        {
            $mention = trim($mention);

            if($mention{0} == '@')
            {
                $content = str_replace($mention, '<a href="https://twitter.com/' .str_replace('@', '', $mention). '">' .$mention. '</a>', $content);
            }

            if($mention{1} == '#')
            {
                $content = str_replace($mention, '<a href="https://twitter.com/hashtag/' .str_replace('#', '', $mention). '">' .$mention. '</a>', $content);
            }
        }

        return $content;
    }
} 