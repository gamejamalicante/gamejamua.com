<?php

/*
 * Copyright (c) 2014 Certadia, SL
 * All rights reserved
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos_contributors")
 */
class Contributor
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * @ORM\ManyToMany(targetEntity="Compo", mappedBy="sponsors")
     */
    protected $composSponsored;

    /**
     * @ORM\ManyToMany(targetEntity="Compo", mappedBy="juries")
     */
    protected $composJudged;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $featured;

    /**
     * @param mixed $composJudged
     */
    public function setComposJudged($composJudged)
    {
        $this->composJudged = $composJudged;
    }

    /**
     * @return mixed
     */
    public function getComposJudged()
    {
        return $this->composJudged;
    }

    /**
     * @param mixed $composSponsored
     */
    public function setComposSponsored($composSponsored)
    {
        $this->composSponsored = $composSponsored;
    }

    /**
     * @return mixed
     */
    public function getComposSponsored()
    {
        return $this->composSponsored;
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

    public function getImageWebPath()
    {
        return 'uploads/contributors/' . $this->nameSlug. '.png';
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * @param mixed $featured
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * @return mixed
     */
    public function getFeatured()
    {
        return $this->featured;
    }
}