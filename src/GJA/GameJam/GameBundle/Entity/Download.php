<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games_downloads")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Download
{
    const PLATFORM_WINDOWS = 1;
    const PLATFORM_MAC = 2;
    const PLATFORM_LINUX = 3;
    const PLATFORM_ANDROID = 4;
    const PLATFORM_WEB = 5;
    const PLATFORM_IOS = 6;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $gamejam;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updatedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $version;

    /**
     * @ORM\Column(type="text")
     */
    protected $fileUrl;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $platforms = array();

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="downloads")
     */
    protected $game;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $size;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @var array
     * TODO: move this to it's entity
     */
    protected $platformMap = array(
        self::PLATFORM_IOS => array(
            'slug' => 'ios',
            'name' => 'iOS (iPhone/iPad)'
        ),
        self::PLATFORM_WEB => array(
            'slug' => 'web',
            'name' => 'Web (HTML5/Javascript)'
        ),
        self::PLATFORM_ANDROID => array(
            'slug' => 'android',
            'name' => 'Android'
        ),
        self::PLATFORM_LINUX => array(
            'slug' => 'linux',
            'name' => 'GNU/Linux'
        ),
        self::PLATFORM_MAC => array(
            'slug' => 'mac',
            'name' => 'Mac'
        ),
        self::PLATFORM_WINDOWS => array(
            'slug' => 'windows',
            'name' => 'Windows (7/8)'
        )
    );

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
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

    /**
     * @return Game
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
     * @param mixed $platforms
     */
    public function setPlatforms($platforms)
    {
        $this->platforms = $platforms;
    }

    /**
     * @return mixed
     */
    public function getPlatforms()
    {
        return $this->platforms;
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

    /**
     * @param mixed $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $gamejam
     */
    public function setGamejam($gamejam)
    {
        $this->gamejam = $gamejam;
    }

    /**
     * @return mixed
     */
    public function getGamejam()
    {
        return $this->gamejam;
    }

    public function isGamejam()
    {
        return $this->gamejam;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    public static function getAvailablePlatforms()
    {
        return [
            self::PLATFORM_WINDOWS => 'Windows',
            self::PLATFORM_MAC => 'Mac',
            self::PLATFORM_LINUX => 'GNU/Linux',
            self::PLATFORM_ANDROID => 'Android',
            self::PLATFORM_WEB => 'HTML5'
        ];
    }

    public function getPlatformString($platform)
    {
        $platforms = self::getAvailablePlatforms();

        return $platforms[$platform];
    }

    /**
     * @param mixed $fileUrl
     */
    public function setFileUrl($fileUrl)
    {
        $this->fileUrl = $fileUrl;
    }

    /**
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->fileUrl;
    }

    public function getPlatformDescription($platform)
    {
        return $this->platformMap[$platform];
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
} 