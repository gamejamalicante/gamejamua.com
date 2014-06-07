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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use GJA\GameJam\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\CompoBundle\Repository\NotificationRepository")
 * @ORM\Table(name="gamejam_compos_notifications")
 */
class Notification
{
    const TYPE_GLOBAL = 1;
    const TYPE_INCLUDE_ONLY = 2;
    const TYPE_EXCLUDE_ONLY = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     * @Gedmo\Slug(fields={"id", "title"})
     */
    protected $nameSlug;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $announce;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="notifications")
     * @ORM\JoinTable(name="gamejam_compos_notifications_users")
     *
     * @var ArrayCollection
     */
    protected $users;

    /**
     * @ORM\ManyToMany(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="readNotifications", cascade={"persist"})
     * @ORM\JoinTable(name="gamejam_compos_notifications_users_read")
     */
    protected $usersRead;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @param mixed $announce
     */
    public function setAnnounce($announce)
    {
        $this->announce = $announce;
    }

    /**
     * @return mixed
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
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
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    function __toString()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function isGlobal()
    {
        return $this->getType() == self::TYPE_GLOBAL;
    }

    public function isPendingForUser($user)
    {
        if(is_null($user))
            return true;

        if($this->isGlobal())
        {
            return !$user->hasReadNotification($this);
        }

        if($this->users->contains($user))
        {
            return !$user->hasReadNotification($this);
        }

        return false;
    }

    public function canUserReadIt($user)
    {
        if($this->isGlobal())
            return true;

        if(is_null($user))
            return false;

        if($this->getType() == self::TYPE_INCLUDE_ONLY)
            return $this->users->contains($user);

        if($this->getType() == self::TYPE_EXCLUDE_ONLY)
            return !$this->users->contains($user);

        return false;
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
     * @param mixed $usersRead
     */
    public function setUsersRead($usersRead)
    {
        $this->usersRead = $usersRead;
    }

    /**
     * @return mixed
     */
    public function getUsersRead()
    {
        return $this->usersRead;
    }

    public function read(User $user)
    {
        if($this->usersRead->contains($user))
            return false;

        $this->usersRead->add($user);

        return true;
    }

    public function addUser(User $user)
    {
        $this->users[] = $user;
    }
} 