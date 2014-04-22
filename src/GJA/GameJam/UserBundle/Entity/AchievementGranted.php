<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
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

    function __construct()
    {
        $this->grantedAt = new \DateTime("now");
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
     * @param mixed $grantedAt
     */
    public function setGrantedAt($grantedAt)
    {
        $this->grantedAt = $grantedAt;
    }

    /**
     * @return mixed
     */
    public function getGrantedAt()
    {
        return $this->grantedAt;
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

    public function __toString()
    {
        return (string) $this->achievement;
    }
} 