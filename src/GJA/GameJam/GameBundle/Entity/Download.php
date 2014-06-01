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
 */
class Download
{
    const PLATFORM_WINDOWS = 1;
    const PLATFORM_MAC = 2;
    const PLATFORM_LINUX = 3;
    const PLATFORM_ANDROID = 3;
    const PLATFORM_WEB = 4;

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
     * @ORM\Column(type="string")
     */
    protected $version;

    /**
     * @ORM\Column(type="text")
     */
    protected $fileUrl;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $platforms;

    /**
     * @ORM\Column(type="text")
     */
    protected $comment;

    /**
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="downloads")
     */
    protected $game;

    /**
     * @ORM\Column(type="string")
     */
    protected $size;

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
} 