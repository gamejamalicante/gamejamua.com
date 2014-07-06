<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Thrace\MediaBundle\Entity\AbstractImage;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_games_media")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Media extends AbstractImage
{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_TIMELAPSE = 3;
    const TYPE_OTHER = 4;
    const TYPE_SHOWCASE = 5;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="media")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $game;

    /**
     * @ORM\OneToOne(targetEntity="Game", mappedBy="image")
     */
    protected $showcaseGame;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $data;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $comment;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type = self::TYPE_IMAGE;

    /**
     * TODO: allow image uploads
     */
    protected $filePath;

    /**
     * @var string
     */
    protected $youtubeId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

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
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
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
        switch ($this->type)
        {
            case self::TYPE_SHOWCASE:
                return $this->getShowcaseGame();
            break;

            default:
                return $this->game;
            break;
        }
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

    public static function getAvailableTypes()
    {
        return [
            self::TYPE_IMAGE => "Imagen",
            self::TYPE_VIDEO => "Vídeo",
            self::TYPE_TIMELAPSE => "Timelapse",
            self::TYPE_OTHER => "Otro (Blender, PSD, etc.)"
        ];
    }

    public function getWebPath()
    {
        return 'uploads' . $this->getUploadDir() . '/' . $this->getName();
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
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getImageUrl()
    {
        if($this->type == self::TYPE_IMAGE)
            return $this->url;

        if($youtubeId = $this->getYoutubeId())
        {
            return "http://img.youtube.com/vi/". $youtubeId. "/2.jpg";
        }

        return '';
    }

    public function getYoutubeId()
    {
        if(!is_null($this->youtubeId))
            return $this->youtubeId;

        if($this->type == self::TYPE_VIDEO or $this->type == self::TYPE_TIMELAPSE)
        {
            if(preg_match("/youtube/i", $this->url))
            {
                parse_str(parse_url($this->url, PHP_URL_QUERY), $youtubeVars);

                if(isset($youtubeVars['v']))
                {
                    $this->youtubeId = $youtubeVars['v'];

                    return $this->youtubeId;
                }
            }
        }

        return null;
    }

    protected function getUploadPath()
    {
        switch($this->type)
        {
            case self::TYPE_IMAGE:
                return 'image';
            break;

            default:
                return 'other';
            break;
        }
    }

    public function getUploadDir()
    {
        $game = $this->getGame();

        return '/game/' . $game->getId() . '/' .$this->getUploadPath();
    }

    public function __toString()
    {
        return $this->getWebPath();
    }

    /**
     * @param mixed $showcaseGame
     */
    public function setShowcaseGame($showcaseGame)
    {
        $this->showcaseGame = $showcaseGame;
    }

    /**
     * @return mixed
     */
    public function getShowcaseGame()
    {
        return $this->showcaseGame;
    }
} 