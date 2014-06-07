<?php

namespace GJA\GameJam\CompoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GJA\GameJam\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos_teams_invitations", uniqueConstraints=
 * {
 *      @ORM\UniqueConstraint(name="unique_inv", columns={"team_id", "sender_id", "target_id", "compo_id", "type"})
 * })
 * @ORM\HasLifecycleCallbacks
 * )
 */
class TeamInvitation
{
    const TYPE_INVITATION = 0;
    const TYPE_REQUEST = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     */
    protected $team;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User")
     */
    protected $sender;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User")
     */
    protected $target;

    /**
     * @ORM\ManyToOne(targetEntity="Compo")
     */
    protected $compo;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(type="string")
     */
    protected $hash;

    /**
     * @param mixed $compo
     */
    public function setCompo($compo)
    {
        $this->compo = $compo;
    }

    /**
     * @return mixed
     */
    public function getCompo()
    {
        return $this->compo;
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
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return User
     */
    public function getTarget()
    {
        return $this->target;
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
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        if(is_null($this->hash))
        {
            $this->hash = sha1($this->getTarget()->getId() . mt_rand(1, 9999) . uniqid());
        }
    }
} 