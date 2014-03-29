<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos_notifications")
 */
class Notification
{
    const TYPE_GLOBAL = 1;
    const TYPE_INCLUDE_ONLY = 2;
    const TYPE_EXCLUDE_ONLY = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $announce;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinTable(name="gamejam_compos_notifications_users")
     */
    protected $users;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;
} 