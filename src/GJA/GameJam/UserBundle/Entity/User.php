<?php

/*
 * Copyright (c) 2013 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_users")
 */
class User extends BaseUser
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
    protected $registeredAt;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\GameBundle\Entity\Game", inversedBy="users")
     * @ORM\JoinTable(name="gamejam_users_games")
     */
    protected $games;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Team", inversedBy="users")
     * @ORM\JoinTable(name="gamejam_users_teams")
     */
    protected $teams;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Compo", mappedBy="soloUsers")
     */
    protected $compos;

    /**
     * @ORM\OneToMany(targetEntity="Shout", mappedBy="user")
     * @ORM\OrderBy({"date"="ASC"})
     */
    protected $shouts;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\GameBundle\Entity\Activity", mappedBy="user")
     */
    protected $activity;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Notification", mappedBy="users")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="AchievementGranted", mappedBy="user")
     */
    protected $achievements;

    /**
     * @ORM\Column(type="integer")
     */
    protected $coins = 0;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nickname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $avatarUrl;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $sex;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $siteUrl;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $presentation;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicProfile = 1;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicEmail = 1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="array")
     */
    protected $readNotifications;

    /**
     * @param mixed $achievements
     */
    public function setAchievements($achievements)
    {
        $this->achievements = $achievements;
    }

    /**
     * @return mixed
     */
    public function getAchievements()
    {
        return $this->achievements;
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

    /**
     * @param mixed $avatarUrl
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;
    }

    /**
     * @return mixed
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
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
     * @param mixed $compos
     */
    public function setCompos($compos)
    {
        $this->compos = $compos;
    }

    /**
     * @return mixed
     */
    public function getCompos()
    {
        return $this->compos;
    }

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
     * @param mixed $nickname
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return $this->nickname ?: $this->username;
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $presentation
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * @return mixed
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * @param mixed $publicEmail
     */
    public function setPublicEmail($publicEmail)
    {
        $this->publicEmail = $publicEmail;
    }

    /**
     * @return mixed
     */
    public function getPublicEmail()
    {
        return $this->publicEmail;
    }

    /**
     * @param mixed $publicProfile
     */
    public function setPublicProfile($publicProfile)
    {
        $this->publicProfile = $publicProfile;
    }

    /**
     * @return mixed
     */
    public function getPublicProfile()
    {
        return $this->publicProfile;
    }

    /**
     * @param mixed $readNotifications
     */
    public function setReadNotifications($readNotifications)
    {
        $this->readNotifications = $readNotifications;
    }

    /**
     * @return mixed
     */
    public function getReadNotifications()
    {
        return $this->readNotifications;
    }

    /**
     * @param mixed $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param mixed $shouts
     */
    public function setShouts($shouts)
    {
        $this->shouts = $shouts;
    }

    /**
     * @return mixed
     */
    public function getShouts()
    {
        return $this->shouts;
    }

    /**
     * @param mixed $siteUrl
     */
    public function setSiteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
    }

    /**
     * @return mixed
     */
    public function getSiteUrl()
    {
        return $this->siteUrl;
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
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param mixed $registeredAt
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    public function getMixedActivity()
    {
        return $this->getShouts();
    }
}