<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\ActivityRepository")
 * @ORM\Table(name="gamejam_compos_activity")
 */
class Activity
{
    const TYPE_MEDIA = 1;
    const TYPE_COINS = 2;
    const TYPE_LIKES = 3;
    const TYPE_INFO_UPDATE = 4;
    const TYPE_CREATION = 5;
    const TYPE_ACHIEVEMENT = 6;
    const TYPE_SHOUT = 7;
    const TYPE_TWITTER = 8;
    const TYPE_DOWNLOAD = 9;
    const TYPE_TEAM_CREATION = 10;
    const TYPE_TEAM_JOIN = 11;

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
     * @ORM\ManyToOne(targetEntity="Compo", inversedBy="activity")
     */
    protected $compo;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="activity")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Game", inversedBy="activity")
     */
    protected $game;

    /**
     * @ORM\ManyToOne(targetEntity="Achievement")
     */
    protected $achievement;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Media")
     */
    protected $media;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Download")
     */
    protected $download;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Team")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $team;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $content;

    public function __construct()
    {
        $this->date = new \DateTime("now");
    }

    /**
     * @param mixed $achievement
     */
    public function setAchievement($achievement)
    {
        $this->achievement = $achievement;
    }

    /**
     * @return mixed
     */
    public function getAchievement()
    {
        return $this->achievement;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
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
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    
    public function getTypeName()
    {
        switch($this->type)
        {
            case self::TYPE_MEDIA:
                return 'media';
            break;

            case self::TYPE_COINS:
                return 'coins';
            break;

            case self::TYPE_LIKES:
                return 'likes';
            break;

            case self::TYPE_INFO_UPDATE:
                return 'info';
            break;

            case self::TYPE_CREATION:
                return 'creation';
            break;

            case self::TYPE_ACHIEVEMENT:
                return 'achievement';
            break;

            case self::TYPE_SHOUT:
                return 'shout';
            break;

            case self::TYPE_TWITTER:
                return 'twitter';
            break;

            case self::TYPE_TEAM_CREATION:
                return 'team_creation';
            break;

            case self::TYPE_TEAM_JOIN:
                return 'team_join';
            break;
        }
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $compo
     */
    public function setCompo($compo)
    {
        $this->compo = $compo;
    }

    /**
     * @return mixed
     */
    public function getCompo()
    {
        return $this->compo;
    }

    /**
     * @param mixed $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return mixed
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param mixed $download
     */
    public function setDownload($download)
    {
        $this->download = $download;
    }

    /**
     * @return mixed
     */
    public function getDownload()
    {
        return $this->download;
    }

    /**
     * @param mixed $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return mixed
     */
    public function getTeam()
    {
        return $this->team;
    }
}