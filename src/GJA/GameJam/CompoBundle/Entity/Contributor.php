<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\ContributorRepository")
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
     * @ORM\Column(type="string", nullable=true)
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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

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
        return 'bundles/gamejamcompo/images/contributors/' . $this->nameSlug. '.png';
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
}