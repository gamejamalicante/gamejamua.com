<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_users_achievements_granted")
 */
class AchievementGranted
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Achievement", inversedBy="granted")
     */
    protected $achievement;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $grantedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="achievements")
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Game")
     */
    protected $game;
} 