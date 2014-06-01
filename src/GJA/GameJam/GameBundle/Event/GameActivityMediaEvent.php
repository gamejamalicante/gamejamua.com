<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\GameBundle\Event;

use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\GameBundle\Entity\Media;

class GameActivityMediaEvent extends GameActivityEvent
{
    /**
     * @var Media
     */
    protected $media;

    public function __construct(Game $game = null, Media $media = null)
    {
        parent::__construct($game);

        $this->media = $media;
    }

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