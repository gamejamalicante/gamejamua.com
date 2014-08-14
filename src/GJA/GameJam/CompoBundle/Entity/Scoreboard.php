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
     * @ORM\Column(type="boolean")
     */
    protected $public = false;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User")
     */
    protected $voter;

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

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @param mixed $audio
     */
    public function setAudio($audio)
    {
        $this->audio = $audio;
    }

    /**
     * @return mixed
     */
    public function getAudio()
    {
        return $this->audio;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $fun
     */
    public function setFun($fun)
    {
        $this->fun = $fun;
    }

    /**
     * @return mixed
     */
    public function getFun()
    {
        return $this->fun;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $graphics
     */
    public function setGraphics($graphics)
    {
        $this->graphics = $graphics;
    }

    /**
     * @return mixed
     */
    public function getGraphics()
    {
        return $this->graphics;
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
     * @param mixed $originality
     */
    public function setOriginality($originality)
    {
        $this->originality = $originality;
    }

    /**
     * @return mixed
     */
    public function getOriginality()
    {
        return $this->originality;
    }

    /**
     * @param mixed $public
     */
    public function setPublic($public)
    {
        $this->public = $public;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
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

    /**
     * @param mixed $voter
     */
    public function setVoter($voter)
    {
        $this->voter = $voter;
    }

    /**
     * @return mixed
     */
    public function getVoter()
    {
        return $this->voter;
    }
}