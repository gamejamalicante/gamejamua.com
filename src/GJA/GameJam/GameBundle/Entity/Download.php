<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games_downloads")
 */
class Download
{
    const PLATFORM_WINDOWS = 1;
    const PLATFORM_MAC = 2;
    const PLATFORM_LINUX = 3;
    const PLATFORM_ANDROID = 3;
    const PLATFORM_WEB = 4;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string")
     */
    protected $version;

    /**
     * @ORM\Column(type="array")
     */
    protected $platforms;

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="downloads")
     */
    protected $game;
} 