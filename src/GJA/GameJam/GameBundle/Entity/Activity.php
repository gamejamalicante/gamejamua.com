<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games_activity")
 */
class Activity
{
    const TYPE_MEDIA = 1;
    const TYPE_COINS = 2;
    const TYPE_LIKES = 3;
    const TYPE_INFO = 4;
    const TYPE_CREATION = 5;
    const TYPE_ACHIEVEMENT = 6;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="activity")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Game")
     */
    protected $game;

    /**
     * @ORM\OneToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Achievement")
     */
    protected $achievement;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(type="array")
     */
    protected $data;
}