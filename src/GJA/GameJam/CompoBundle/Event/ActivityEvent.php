<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Event;

use GJA\GameJam\CompoBundle\Entity\Activity;
use Symfony\Component\EventDispatcher\Event;

class ActivityEvent extends Event
{
    /**
     * @var Activity
     */
    protected $activity;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
    }

    /**
     * @param \GJA\GameJam\CompoBundle\Entity\Activity $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Entity\Activity
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
