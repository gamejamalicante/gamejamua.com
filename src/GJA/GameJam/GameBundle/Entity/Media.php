<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games_media")
 */
class Media
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_TIMELAPSE = 3;

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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="media")
     */
    protected $game;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $filePath;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $data;

    /**
     * @ORM\Column(type="string")
     */
    protected $comment;
} 