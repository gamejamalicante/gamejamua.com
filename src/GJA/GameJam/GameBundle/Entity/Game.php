<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\GameBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use GJA\GameJam\ChallengeBundle\Entity\Challenge;
use GJA\GameJam\CompoBundle\Entity\Compo;
use GJA\GameJam\CompoBundle\Entity\Diversifier;
use GJA\GameJam\CompoBundle\Entity\Scoreboard;
use GJA\GameJam\CompoBundle\Entity\Team;
use GJA\GameJam\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Thrace\MediaBundle\Model\ImageInterface;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\GameBundle\Repository\GameRepository")
 * @ORM\Table(name="gamejam_games")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Game
{
    const WINNER_FIRST = 1;
    const WINNER_SECOND = 2;
    const WINNER_THIRD = 3;

    const MENTION_GRAPHICS = 1;
    const MENTION_AUDIO = 2;
    const MENTION_ORIGINALITY = 3;
    const MENTION_ENTERTAINMENT = 4;
    const MENTION_THEME = 5;

    const TYPE_NORMAL = 1;
    const TYPE_BOARD = 2;

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
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"})
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="Media", cascade={"all"}, orphanRemoval=true, fetch="EAGER", inversedBy="showcaseGame")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $image;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $oldUrl;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Diversifier")
     * @ORM\JoinTable(name="gamejam_games_diversifiers")
     */
    protected $diversifiers;

    /**
     * @ORM\OneToMany(targetEntity="Media", mappedBy="game", cascade={"all"})
     * @var ArrayCollection
     */
    protected $media;

    /**
     * @ORM\OneToMany(targetEntity="Download", mappedBy="game", cascade={"persist", "remove"})
     * @ORM\OrderBy({"gamejam"="DESC", "version"="ASC"})
     * @var ArrayCollection
     */
    protected $downloads;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Compo", inversedBy="games")
     */
    protected $compo;

    /**
     * @ORM\Column(type="integer")
     */
    protected $likes = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $coins = 0;

    /**
     * @ORM\OneToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Team", inversedBy="game")
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="games")
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Activity", mappedBy="game")
     * @ORM\OrderBy({"date"="DESC"})
     */
    protected $activity;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $winner;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $mentions;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User")
     * @ORM\JoinTable(name="gamejam_games_user_likes")
     */
    protected $userLike;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\ChallengeBundle\Entity\Challenge", mappedBy="game")
     */
    protected $challenges;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Scoreboard", mappedBy="game")
     */
    protected $scoreboard;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type = self::TYPE_NORMAL;

    /**
     * @var bool
     */
    protected $isNew = false;

    protected $positions = array(
        self::WINNER_FIRST => "first",
        self::WINNER_SECOND => "second",
        self::WINNER_THIRD => "third"
    );

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->downloads = new ArrayCollection();
    }

    /**
     * @param mixed $compo
     */
    public function setCompo($compo)
    {
        $this->compo = $compo;
    }

    /**
     * @return Compo
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

    public function addDiversifier(Diversifier $diversifier)
    {
        $this->diversifiers[] = $diversifier;
    }

    /**
     * @param mixed $downloads
     */
    public function setDownloads($downloads)
    {
        foreach($downloads as $download)
        {
            $this->addDownload($download);
        }
    }

    /**
     * @return Download[]
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    public function addDownload(Download $download)
    {
        $download->setGame($this);

        $this->downloads->add($download);
    }

    public function removeDownload(Download $download)
    {
        $this->downloads->remove($download);
    }

    public function addMedia(Media $media)
    {
        $media->setGame($this);

        $this->media->add($media);
    }

    public function removeMedia(Media $media)
    {
        $this->media->remove($media);
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
    public function setImage(Media $image)
    {
        $this->image = $image;
        $this->image->setShowcaseGame($this);
        $this->image->setType(Media::TYPE_SHOWCASE);
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
     * @return Media[]
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
     * @return Team
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

    public function getGamejamDownload()
    {
        foreach($this->getDownloads() as $download)
        {
            if($download->isGamejam())
                return $download;
        }

        return null;
    }

    public function isNew()
    {
        return $this->isNew;
    }

    /**
     * @param boolean $isNew
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;
    }

    /**
     * @return boolean
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $mentions
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;
    }

    /**
     * @return mixed
     */
    public function getMentions()
    {
        return $this->mentions;
    }

    public function addMention($mention)
    {
        $this->mentions[] = $mention;
    }

    public function getPosition()
    {
        if(!is_null($this->winner))
        {
            return $this->positions[$this->winner];
        }

        return null;
    }

    public function isUserAllowedToEdit(User $user)
    {
        if($user->hasRole('ROLE_ADMIN'))
            return true;

        if($this->user === $user)
            return true;

        if(!$this->getTeam())
            return false;

        foreach($this->getTeam()->getUsers() as $teamMember)
        {
            if($teamMember === $user)
                return true;
        }

        return false;
    }

    public function isOwner(User $user)
    {
        if(!$this->getTeam()) {
            return $this->getUser() === $user;
        }

        foreach($this->getTeam()->getUsers() as $teamMember)
        {
            if($teamMember === $user)
                return true;
        }

        return false;
    }

    public function isUserAllowedToDelete(User $user)
    {
        if(!$this->getTeam())
        {
            if($user === $this->getUser())
                return true;
        }
        else
        {
            if($user === $this->getTeam()->getLeader())
                return true;
        }

        return false;
    }

    public function giveCoins($coins)
    {
        $this->coins += (int) $coins;
    }

    /**
     * @param array $positions
     */
    public function setPositions($positions)
    {
        $this->positions = $positions;
    }

    /**
     * @return array
     */
    public function getPositions()
    {
        return $this->positions;
    }

    /**
     * @param mixed $userLike
     */
    public function setUserLike($userLike)
    {
        $this->userLike = $userLike;
    }

    /**
     * @return mixed
     */
    public function getUserLike()
    {
        return $this->userLike;
    }

    public function like(User $user)
    {
        if($this->userLike->contains($user))
            return false;

        $this->userLike->add($user);

        $this->likes++;

        return true;
    }

    public function hasUserAlreadyLiked($user)
    {
        if(is_null($user))
            return false;

        return $this->userLike->contains($user);
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @param mixed $challenges
     */
    public function setChallenges($challenges)
    {
        $this->challenges = $challenges;
    }

    /**
     * @return mixed
     */
    public function getChallenges()
    {
        return $this->challenges;
    }

    public function addChallenge(Challenge $challenge)
    {
        $this->challenges[] = $challenge;
    }

    /**
     * @param mixed $oldUrl
     */
    public function setOldUrl($oldUrl)
    {
        $this->oldUrl = $oldUrl;
    }

    /**
     * @return mixed
     */
    public function getOldUrl()
    {
        return $this->oldUrl;
    }

    /**
     * @param mixed $scoreboard
     */
    public function setScoreboard($scoreboard)
    {
        $this->scoreboard = $scoreboard;
    }

    /**
     * @return Scoreboard[]
     */
    public function getScoreboard()
    {
        return $this->scoreboard;
    }

    public function getScoreboardByVoter(User $user)
    {
        foreach($this->getScoreboard() as $scoreboardItem)
        {
            if ($scoreboardItem->getVoter() === $user)
                return $scoreboardItem;
        }

        return null;
    }

    public function getTotalGraphicsPoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getGraphics();
        }

        return $total;
    }

    public function getTotalAudioPoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getAudio();
        }

        return $total;
    }

    public function getTotalPoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getTotal();
        }

        return $total;
    }

    public function getTotalFunPoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getFun();
        }

        return $total;
    }

    public function getTotalThemePoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getTheme();
        }

        return $total;
    }

    public function getTotalOriginalityPoints()
    {
        $total = 0;

        foreach($this->getScoreboard() as $scoreboardItem)
        {
            $total += $scoreboardItem->getOriginality();
        }

        return $total;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}