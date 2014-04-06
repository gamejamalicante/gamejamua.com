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
class Activity implements ActivityInterface
{
    const TYPE_MEDIA = 1;
    const TYPE_COINS = 2;
    const TYPE_LIKES = 3;
    const TYPE_INFO = 4;
    const TYPE_CREATION = 5;
    const TYPE_ACHIEVEMENT = 6;
    const TYPE_SHOUT = 7;

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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="activity")
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
     * @ORM\Column(type="string")
     */
    protected $content;

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
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
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

            case self::TYPE_INFO:
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
}