<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\ChallengeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_challenges_causes")
 * @ORM\HasLifecycleCallbacks
 */
class Cause
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="float")
     */
    protected $maxAmount;

    /**
     * @ORM\OneToMany(targetEntity="Challenge", mappedBy="cause")
     */
    protected $challenges;

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
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\CompoBundle\Entity\Compo")
     */
    protected $gamejam;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $startAt;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $endAt;

    /**
     * @ORM\Column(type="float")
     */
    protected $donationPerCompletion;

    /**
     * @param mixed $challenges
     */
    public function setChallenges($challenges)
    {
        $this->challenges = $challenges;
    }

    /**
     * @return Challenge[]
     */
    public function getChallenges()
    {
        return $this->challenges;
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
     * @param mixed $maxAmount
     */
    public function setMaxAmount($maxAmount)
    {
        $this->maxAmount = $maxAmount;
    }

    /**
     * @return mixed
     */
    public function getMaxAmount()
    {
        return $this->maxAmount;
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
     * @param mixed $endAt
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
    }

    /**
     * @return mixed
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param mixed $startAt
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;
    }

    /**
     * @return mixed
     */
    public function getStartAt()
    {
        return $this->startAt;
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
     * @return Donation[]
     */
    public function getDonations()
    {
        $donations = array();

        foreach ($this->getChallenges() as $challenge) {
            foreach ($challenge->getDonations() as $donation) {
                $donations[] = $donation;
            }
        }

        return $donations;
    }

    public function getGames()
    {
        $games = new ArrayCollection();

        foreach ($this->getChallenges() as $challenge) {
            if (!$games->contains($challenge->getGame())) {
                $games->add($challenge->getGame());
            }
        }

        return $games;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param mixed $donationPerCompletion
     */
    public function setDonationPerCompletion($donationPerCompletion)
    {
        $this->donationPerCompletion = $donationPerCompletion;
    }

    /**
     * @return mixed
     */
    public function getDonationPerCompletion()
    {
        return $this->donationPerCompletion;
    }
}
