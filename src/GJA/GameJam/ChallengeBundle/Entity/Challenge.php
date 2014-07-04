<?php


namespace GJA\GameJam\ChallengeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\ActivityRepository")
 * @ORM\Table(name="gamejam_challenges_challenge")
 */
class Challenge
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
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\GameBundle\Entity\Game")
     */
    protected $game;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $completed = false;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $completions = 0;

    /**
     * @ORM\Column(type="string")
     */
    protected $token;

    /**
     * @param mixed $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    /**
     * @return mixed
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $completions
     */
    public function setCompletions($completions)
    {
        $this->completions = $completions;
    }

    /**
     * @return mixed
     */
    public function getCompletions()
    {
        return $this->completions;
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
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    public function isCompleted()
    {
        return $this->completed;
    }
} 