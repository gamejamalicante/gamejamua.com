<?php

/*
 * Copyright 2014 (c) Alberto Fernández
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use GJA\GameJam\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\CompoRepository")
 * @ORM\Table(name="gamejam_compos")
 */
class Compo
{
    const TEAM_FORMATION_PERIOD = "PT4H";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal", precision=2, nullable=true)
     */
    protected $memberFee;

    /**
     * @ORM\Column(type="decimal", precision=2, nullable=true)
     */
    protected $normalFee;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"name"})
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $open = false;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startAt;

    /**
     * @ORM\Column(type="string")
     */
    protected $period;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $applicationStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $applicationEndAt;

    /**
     * @ORM\OneToOne(targetEntity="Theme", inversedBy="compo", cascade={"persist"})
     */
    protected $theme;

    /**
     * @ORM\ManyToMany(targetEntity="Contributor", inversedBy="composJudged")
     * @ORM\JoinTable(name="gamejam_compos_compos_juries")
     */
    protected $juries;

    /**
     * @ORM\ManyToMany(targetEntity="Contributor", inversedBy="composSponsored")
     * @ORM\JoinTable(name="gamejam_compos_compos_sponsors")
     * @ORM\OrderBy({"featured"="DESC"})
     */
    protected $sponsors;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\GameBundle\Entity\Game", mappedBy="compo")
     */
    protected $games;

    /**
     * @ORM\OneToMany(targetEntity="CompoApplication", mappedBy="compo")
     */
    protected $applications;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $maxPeople;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $maxTeamMembers = 3;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\CompoBundle\Entity\Activity", mappedBy="compo")
     * @ORM\OrderBy({"date"="DESC"})
     */
    protected $activity;

    /**
     * @ORM\OneToMany(targetEntity="Team", mappedBy="compo")
     */
    protected $teams;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="compo")
     */
    protected $pages;

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
     * @param mixed $juries
     */
    public function setJuries($juries)
    {
        $this->juries = $juries;
    }

    /**
     * @return mixed
     */
    public function getJuries()
    {
        return $this->juries;
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
     * @param mixed $period
     */
    public function setPeriod($period)
    {
        $this->period = $period;
    }

    /**
     * @return mixed
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * @param mixed $sponsors
     */
    public function setSponsors($sponsors)
    {
        $this->sponsors = $sponsors;
    }

    /**
     * @return mixed
     */
    public function getSponsors()
    {
        return $this->sponsors;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @param mixed $theme
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    function __toString()
    {
        return $this->name;
    }

    public function isOpen()
    {
        return $this->open;
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
     * @param mixed $open
     */
    public function setOpen($open)
    {
        $this->open = $open;
    }

    /**
     * @return mixed
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * @param mixed $maxPeople
     */
    public function setMaxPeople($maxPeople)
    {
        $this->maxPeople = $maxPeople;
    }

    /**
     * @return mixed
     */
    public function getMaxPeople()
    {
        return $this->maxPeople;
    }

    public function hasStarted()
    {
        $now = new \DateTime('now');

        if($this->getStartAt() <= $now)
            return true;

        return false;
    }

    public function isRunning()
    {
        if(!$this->hasStarted())
            return false;

        if($this->hasFinished())
            return false;

        return true;
    }

    public function getTimeToStart()
    {
        if($this->hasStarted())
            return false;

        return $this->getStartAt()->diff(new \DateTime("now"));
    }

    public function getDaysToStart()
    {
        if($daysToStart = $this->getTimeToStart())
        {
            return $daysToStart->days;
        }

        return 0;
    }

    public function endAt()
    {
        $period = new \DateInterval($this->period);

        $startAt = clone $this->getStartAt();

        return $startAt->add($period);
    }

    public function getEndAt()
    {
        return $this->endAt();
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
     * @param CompoApplication[] $applications
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;
    }

    /**
     * @return CompoApplication[]
     */
    public function getApplications()
    {
        return $this->applications;
    }

    public function getApplicationForUser(User $user)
    {
        foreach($this->getApplications() as $application)
        {
            if($application->getUser() === $user)
                return $application;
        }

        return null;
    }

    /**
     * @param mixed $applicationStartAt
     */
    public function setApplicationStartAt($applicationStartAt)
    {
        $this->applicationStartAt = $applicationStartAt;
    }

    /**
     * @return mixed
     */
    public function getApplicationStartAt()
    {
        return $this->applicationStartAt;
    }

    /**
     * @param mixed $applicationEndAt
     */
    public function setApplicationEndAt($applicationEndAt)
    {
        $this->applicationEndAt = $applicationEndAt;
    }

    /**
     * @return mixed
     */
    public function getApplicationEndAt()
    {
        return $this->applicationEndAt;
    }

    public function getTeams()
    {
        return $this->teams;
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
     * @param mixed $membershipFee
     */
    public function setMemberFee($membershipFee)
    {
        $this->memberFee = $membershipFee;
    }

    /**
     * @return mixed
     */
    public function getMemberFee()
    {
        return $this->memberFee;
    }

    /**
     * @param mixed $normalFee
     */
    public function setNormalFee($normalFee)
    {
        $this->normalFee = $normalFee;
    }

    /**
     * @return mixed
     */
    public function getNormalFee()
    {
        return $this->normalFee;
    }

    public function getOpenPlaces()
    {
        $validApplications = 0;

        foreach($this->getApplications() as $application)
        {
            if(($application->isCompleted() || $application->isInProgress()) && $application->getModality() != CompoApplication::MODALITY_FREE)
                $validApplications++;
        }

        return $this->maxPeople - $validApplications;
    }

    public function isFull()
    {
        return $this->getOpenPlaces() == 0;
    }

    public function getCompletedApplications()
    {
        return $this->maxPeople - $this->getOpenPlaces();
    }

    public function isTeamFormationOpen()
    {
        $now = new \DateTime("now");

        $startDate = $this->getStartAt();

        if($now > $startDate->add(new \DateInterval(self::TEAM_FORMATION_PERIOD)))
            return false;

        return true;
    }

    public function getSecondsToStartTime()
    {
        if($this->hasStarted())
            return 0;

        return $this->startAt->getTimestamp()-(new \DateTime("now"))->getTimestamp();
    }

    /**
     * @param mixed $maxTeamMembers
     */
    public function setMaxTeamMembers($maxTeamMembers)
    {
        $this->maxTeamMembers = $maxTeamMembers;
    }

    /**
     * @return mixed
     */
    public function getMaxTeamMembers()
    {
        return $this->maxTeamMembers;
    }

    public function getSecondsToFinish()
    {
        if(!$this->hasStarted())
            return 0;

        $now = new \DateTime('now');

        return $this->endAt()->getTimestamp()-$now->getTimestamp();
    }

    public function hasFinished()
    {
        if(!$this->hasStarted())
            return false;

        $now = new \DateTime("now");

        if($now > $this->endAt())
            return true;

        return false;
    }

    public function getJoinedMembers()
    {
        $members = array();

        foreach($this->getApplications() as $application)
        {
            if($application->isCompleted())
                $members[] = $application->getUser();
        }

        return $members;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->pages;
    }
}