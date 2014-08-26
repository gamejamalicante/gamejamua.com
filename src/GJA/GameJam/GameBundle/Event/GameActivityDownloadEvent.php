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

use GJA\GameJam\GameBundle\Entity\Download;
use GJA\GameJam\GameBundle\Entity\Game;
use GJA\GameJam\UserBundle\Entity\User;

class GameActivityDownloadEvent extends GameActivityEvent
{
    /**
     * @var Download
     */
    protected $download;

    public function __construct(User $user = null, Game $game = null, Download $download = null)
    {
        parent::__construct($user, $game);

        $this->download = $download;
    }

    /**
     * @param \GJA\GameJam\GameBundle\Entity\Download $download
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }

    /**
     * @return \GJA\GameJam\GameBundle\Entity\Download
     */
    public function getDownload()
    {
        return $this->download;
    }
}
