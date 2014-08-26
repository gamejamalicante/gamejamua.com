<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\ChallengeBundle\Event;

use GJA\GameJam\ChallengeBundle\Entity\Donation;
use Symfony\Component\EventDispatcher\Event;

class DonationCompletedEvent extends Event
{
    protected $donation;

    public function __construct(Donation $donation)
    {
        $this->donation = $donation;
    }

    public function getDonation()
    {
        return $this->donation;
    }
} 