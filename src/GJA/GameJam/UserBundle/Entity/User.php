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
    protected $coins;

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
    protected $publicProfile;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $publicEmail;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="array")
     */
    protected $readNotifications;
}