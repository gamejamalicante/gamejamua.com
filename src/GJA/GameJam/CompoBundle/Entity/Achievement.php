<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GJA\GameJam\CompoBundle\Achievement\ConstraintCheckerInterface;
use GJA\GameJam\CompoBundle\Achievement\GranterInterface;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\AchievementRepository")
 * @ORM\Table(name="gamejam_compos_achievements")
 */
class Achievement
{
    const TYPE_GAME = 1;
    const TYPE_USER = 2;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\OneToMany(targetEntity="GJA\GameJam\UserBundle\Entity\AchievementGranted", mappedBy="achievement")
     */
    protected $granted;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $granter;

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
     * @param mixed $granted
     */
    public function setGranted($granted)
    {
        $this->granted = $granted;
    }

    /**
     * @return mixed
     */
    public function getGranted()
    {
        return $this->granted;
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

    function __toString()
    {
        return $this->name;
    }

    /**
     * @param mixed $granter
     */
    public function setGranter($granter)
    {
        $this->granter = $granter;
    }

    /**
     * @return mixed
     */
    public function getGranter()
    {
        return $this->granter;
    }

    public function grant(Activity $activity)
    {
        /** @var GranterInterface $class */
        if($class = $this->getGranter())
        {
            return $class::grant($activity);
        }

        return false;
    }
} 