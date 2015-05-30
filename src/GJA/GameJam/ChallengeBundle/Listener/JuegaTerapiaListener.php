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

use GJA\GameJam\ChallengeBundle\Event\ChallengeCompletedEvent;
use GJA\GameJam\ChallengeBundle\Service\JuegaTerapiaCause;

class JuegaTerapiaListener
{
    /**
     * @var JuegaTerapiaCause
     */
    protected $juegaTerapia;

    public function __construct(JuegaTerapiaCause $juegaTerapiaCause)
    {
        $this->juegaTerapia = $juegaTerapiaCause;
    }

    public function onChallengeCompleted(ChallengeCompletedEvent $challengeCompletedEvent)
    {
        $challenge = $challengeCompletedEvent->getChallenge();

        $donationAmount = $this->juegaTerapia->processChallengeCompletion($challenge);

        $challengeCompletedEvent->addExtra('donation_amount', $donationAmount);
    }
}
