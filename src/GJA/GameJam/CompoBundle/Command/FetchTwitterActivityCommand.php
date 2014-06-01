<?php

namespace GJA\GameJam\CompoBundle\Command;

use Doctrine\ORM\EntityManager;
use Endroid\Twitter\Twitter;
use GJA\GameJam\CompoBundle\Entity\Activity;
use GJA\GameJam\CompoBundle\Repository\ActivityRepository;
use GJA\GameJam\GameBundle\GameJamGameEvents;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setDescription("Fetch twitter activity and generates events");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Twitter $twitter */
        $twitter = $this->getContainer()->get('endroid.twitter');

        $query = $this->prepareQuery();

        try
        {
            $response = $twitter->query("search/tweets", "get", "json", array('q' => $query));
        }
        catch(\Exception $ex)
        {
            $output->writeln("Failed fetching Tweets: ". $ex->getMessage());
            exit(0x255);
        }

        $response = json_decode($response->getContent());

        $tweets = $response->statuses;

        if(empty($tweets))
        {
            $output->writeln("No tweets fetched");
        }

        foreach($tweets as $tweet)
        {
            $user = $this->findUserFromTwitterUsername($tweet->user->screen_name);

            $activity = new Activity();
            $activity->setType(Activity::TYPE_TWITTER);
            $activity->setUser($user);
            $activity->setCompo($this->getCurrentCompo());

            $content = array(
                'content' => $tweet->text
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

    protected function prepareQuery()
    {
        /** @var ActivityRepository $activityRepository */
        $activityRepository = $this->entityManager->getRepository("GameJamCompoBundle:Activity");

        $hashtags = $this->getContainer()->getParameter("twitter.hashtags");
        $username = "@" . $this->getContainer()->getParameter("twitter.username");

        foreach($hashtags as $key => $hashtag)
        {
            $hashtags[$key] = "#" . $hashtag;
        }

        $hashtags[] = $username;

        $query = implode($hashtags, " OR ");

        $lastInteraction = $activityRepository->findLastTwitterInteraction();

        if($lastInteraction)
        {
            $query .= " since:" . $lastInteraction->getDate()->format("Y-m-d");
        }

        return $query;
    }
} 