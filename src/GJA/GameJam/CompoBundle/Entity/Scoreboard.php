<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos_scoreboards")
 */
class Scoreboard
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Game")
     */
    protected $game;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $graphics;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $audio;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $originality;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $fun;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $theme;
}