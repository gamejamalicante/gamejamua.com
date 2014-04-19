<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
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

    function __construct(Activity $activity)
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