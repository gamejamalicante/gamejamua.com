<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\ChallengeBundle\Service;

use Doctrine\ORM\EntityManager;
use GJA\GameJam\ChallengeBundle\Entity\Cause;
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\ChallengeBundle\Entity\Donation;
use Symfony\Component\HttpFoundation\RequestStack;

class JuegaTerapiaCause
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Cause
     */
    protected $cause;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    public function __construct(EntityManager $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function processChallengeCompletion(Challenge $challenge)
    {
        if (!$this->isChallengeSuitable($challenge)) {
            return 0.0;
        }

        if ($this->isDonationDuplicated($challenge)) {
            return 0.0;
        }

        $donationAmount = $this->calculateDonationAmount();

        $donation = new Donation();
        $donation->setAmount($donationAmount);
        $donation->setUser($this->requestStack->getCurrentRequest()->getClientIp());
        $donation->setChallenge($challenge);

        $this->entityManager->persist($donation);
        $this->entityManager->flush();

        return $donationAmount;
    }

    protected function getCause()
    {
        if ($this->cause !== null) {
            $this->cause = $this->entityManager->getRepository('GameJamChallengeBundle:Cause')->findOneByNameSlug('juega-terapia-more-than-gamers');
        }

        return $this->cause;
    }

    protected function calculateDonationAmount()
    {
        return 0.5;
    }

    private function isDonationDuplicated(Challenge $challenge)
    {
        $result = $this->entityManager->getRepository('GameJamChallengeBundle:Donation')->findBy(
            ['challenge' => $challenge, 'user' => $this->requestStack->getCurrentRequest()->getClientIp()]
        );

        if ($result) {
            return true;
        }

        return false;
    }

    private function isChallengeSuitable(Challenge $challenge)
    {
        return $challenge->getCause() === $this->getCause() && !$challenge->isCompleted() && !$challenge->getTemp();
    }
} 