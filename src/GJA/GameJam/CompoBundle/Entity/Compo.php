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
     * @ORM\Column(type="string")
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $open = false;

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
     * @ORM\JoinTable(name="gamejam_compos_compos_juries")
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

    /**
     * @ORM\Column(type="smallint")
     */
    protected $maxPeople;

    /**
     * @param mixed $games
     */
    public function setGames($games)
    {
        $this->games = $games;
    }

    /**
     * @return mixed
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $juries
     */
    public function setJuries($juries)
    {
        $this->juries = $juries;
    }

    /**
     * @return mixed
     */
    public function getJuries()
    {
        return $this->juries;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $soloUsers
     */
    public function setSoloUsers($soloUsers)
    {
        $this->soloUsers = $soloUsers;
    }

    /**
     * @return mixed
     */
    public function getSoloUsers()
    {
        return $this->soloUsers;
    }

    /**
     * @param mixed $sponsors
     */
    public function setSponsors($sponsors)
    {
        $this->sponsors = $sponsors;
    }

    /**
     * @return mixed
     */
    public function getSponsors()
    {
        return $this->sponsors;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    /**
     * @return mixed
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param mixed $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    function __toString()
    {
        return $this->name;
    }

    public function isOpen()
    {
        return $this->open;
    }

    /**
     * @param mixed $nameSlug
     */
    public function setNameSlug($nameSlug)
    {
        $this->nameSlug = $nameSlug;
    }

    /**
     * @return mixed
     */
    public function getNameSlug()
    {
        return $this->nameSlug;
    }

    /**
     * @param mixed $open
     */
    public function setOpen($open)
    {
        $this->open = $open;
    }

    /**
     * @return mixed
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @param mixed $maxPeople
     */
    public function setMaxPeople($maxPeople)
    {
        $this->maxPeople = $maxPeople;
    }

    /**
     * @return mixed
     */
    public function getMaxPeople()
    {
        return $this->maxPeople;
    }
}