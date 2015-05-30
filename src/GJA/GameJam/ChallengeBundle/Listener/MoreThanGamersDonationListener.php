<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\ChallengeBundle\Listener;

use Buzz\Browser;
use Doctrine\ORM\EntityManager;
use GJA\GameJam\ChallengeBundle\Entity\Donation;
use GJA\GameJam\ChallengeBundle\Entity\MtgSession;
use GJA\GameJam\ChallengeBundle\Event\DonationCompletedEvent;
use Psr\Log\LoggerInterface;

class MoreThanGamersDonationListener
{
    const SESSION_URL = 'http://morethangamers.com/morethanplataform/index.php/restapi/developer/session';
    const TOKEN_URL = 'http://morethangamers.com/morethanplataform/index.php/restapi/developer/token';
    const ANONYMOUS_URL = 'http://morethangamers.com/morethanplataform/index.php/restapi/developer/anonymuschallenge';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var string
     */
    protected $socketId;

    /**
     * @var string
     */
    protected $socketPassword;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $gameId;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(
        EntityManager $entityManager,
        Browser $browser,
        LoggerInterface $logger,
        $socketId,
        $socketPassword,
        $secret,
        $gameId
    ) {
        $this->socketId = $socketId;
        $this->socketPassword = $socketPassword;
        $this->secret = $secret;
        $this->gameId = $gameId;
        $this->entityManager = $entityManager;
        $this->browser = $browser;
        $this->logger = $logger;
    }

    public function onDonationCompleted(DonationCompletedEvent $donationCompletedEvent)
    {
        $donation = $donationCompletedEvent->getDonation();

        try {
            $this->doCompleteDonation($donation);
        } catch (\Exception $ex) {
            $this->logger->error('MTG (Exception): '.$ex->getMessage());
        }
    }

    public function doCompleteDonation(Donation $donation)
    {
        if (!$session = $this->getSessionFromIp($donation->getUser())) {
            $session = $this->createNewSession($donation->getUser());
        }

        // get socket token
        $token = $this->getChallengeToken();

        // complete anonymous challenge
        $this->completeAnonymousChallenge($token, $session->getSessionId());
    }

    private function getSessionFromIp($ip)
    {
        return $this->entityManager->getRepository('GameJamChallengeBundle:MtgSession')->findOneByIp($ip);
    }

    /**
     * @param $ip
     *
     * @return MtgSession
     */
    private function createNewSession($ip)
    {
        $parameters = array(
            'gameId' => $this->gameId,
            'secretCode' => $this->secret,
        );

        $params = http_build_query($parameters);

        $response = $this->browser->post(self::SESSION_URL, array(), $params);

        $this->logger->info('MTG (Session): '.$response->getContent(), $parameters);

        $response = json_decode(trim($response->getContent()));

        $sessionId = $response->secretCode;

        $session = new MtgSession();
        $session->setIp($ip);
        $session->setSessionId($sessionId);

        $this->entityManager->persist($session);
        $this->entityManager->flush($session);

        return $session;
    }

    private function getChallengeToken()
    {
        $parameters = array(
            'gameId' => $this->gameId,
            'socketId' => $this->socketId,
            'password' => $this->socketPassword,
        );

        $params = http_build_query($parameters);

        $response = $this->browser->post(self::TOKEN_URL, array(), $params);

        $this->logger->info('MTG (Token): '.$response->getContent(), $parameters);

        $response = json_decode(trim($response->getContent()));

        return $response->token;
    }

    private function completeAnonymousChallenge($token, $sessionId)
    {
        $parameters = array(
            'gameId' => $this->gameId,
            'socketId' => $this->socketId,
            'token' => $token,
            'sessionId' => $sessionId,
        );

        $params = http_build_query($parameters);

        $response = $this->browser->post(self::ANONYMOUS_URL, array(), $params);

        $this->logger->info('MTG (AnonChall): '.$response->getContent(), $parameters);
    }
}
