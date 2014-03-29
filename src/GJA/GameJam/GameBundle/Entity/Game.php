<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games")
 */
class Game
{
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
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Diversifier")
     * @ORM\JoinTable(name="gamejam_games_diversifiers")
     */
    protected $diversifiers;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="game")
     */
    protected $media;

    /**
     * @ORM\OneToMany(targetEntity="Download", mappedBy="game")
     */
    protected $downloads;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Compo", inversedBy="games")
     */
    protected $compo;

    /**
     * @ORM\Column(type="integer")
     */
    protected $likes;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User", mappedBy="games")
     */
    protected $users;
}