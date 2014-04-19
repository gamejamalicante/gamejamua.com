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
    const PLATFORM_PC = 1;
    const PLATFORM_MOBILE = 2;

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
     * @ORM\Column(type="string")
     */
    protected $nameSlug;

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
     * @ORM\Column(type="integer")
     */
    protected $coins;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Team", inversedBy="games")
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="games")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Activity", mappedBy="game")
     */
    protected $activity;

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
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $diversifiers
     */
    public function setDiversifiers($diversifiers)
    {
        $this->diversifiers = $diversifiers;
    }

    /**
     * @return mixed
     */
    public function getDiversifiers()
    {
        return $this->diversifiers;
    }

    /**
     * @param mixed $downloads
     */
    public function setDownloads($downloads)
    {
        $this->downloads = $downloads;
    }

    /**
     * @return mixed
     */
    public function getDownloads()
    {
        return $this->downloads;
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
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $likes
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }

    /**
     * @return mixed
     */
    public function getLikes()
    {
        return $this->likes;
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
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    function __toString()
    {
        return $this->name;
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

    /**
     * @param mixed $coins
     */
    public function setCoins($coins)
    {
        $this->coins = $coins;
    }

    /**
     * @return mixed
     */
    public function getCoins()
    {
        return $this->coins;
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

    /**
     * @param mixed $activity
     */
    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }


}