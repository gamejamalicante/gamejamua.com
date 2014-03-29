<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos")
 */
class Compo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startAt;

    /**
     * @ORM\Column(type="string")
     */
    protected $period;

    /**
     * @ORM\OneToOne(targetEntity="Theme")
     */
    protected $theme;

    /**
     * @ORM\ManyToMany(targetEntity="Contributor", inversedBy="composJudged")
     * @ORM\JoinTable(name="gamejam_compos_compos_contributors")
     */
    protected $juries;

    /**
     * @ORM\ManyToMany(targetEntity="Contributor", inversedBy="composSponsored")
     * @ORM\JoinTable(name="gamejam_compos_compos_sponsors")
     */
    protected $sponsors;

    /**
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="compos")
     * @ORM\JoinTable(name="gamejam_compos_compos_teams")
     */
    protected $teams;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\GameBundle\Entity\Game", mappedBy="compo")
     */
    protected $games;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="compos")
     * @ORM\JoinTable(name="gamejam_compos_solousers")
     */
    protected $soloUsers;
}