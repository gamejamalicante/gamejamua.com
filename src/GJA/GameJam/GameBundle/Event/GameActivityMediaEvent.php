<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\GameBundle\Event;

use GJA\GameJam\GameBundle\Entity\Media;

class GameActivityMediaEvent extends GameActivityEvent
{
    /**
     * @var Media
     */
    protected $media;

    /**
     * @param \GJA\GameJam\GameBundle\Entity\Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return \GJA\GameJam\GameBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
} 