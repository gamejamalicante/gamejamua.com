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
use GJA\GameJam\UserBundle\Entity\Order;

/**
 * @ORM\Entity
 * @ORM\Table(name="gamejam_compos_applications")
 */
class CompoApplication
{
    const MODALITY_NORMAL = 1;
    const MODALITY_OUT_OF_COMPO = 2;
    const MODALITY_FREE = 3;

    const TYPE_DIGITAL = 1;
    const TYPE_BOARD_GAME = 2;

    const LOCK_TTL = 'PT10M';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\ManyToOne(targetEntity="Compo", inversedBy="applications")
     */
    protected $compo;

    /**
     * @ORM\ManyToOne(targetEntity="GJA\GameJam\UserBundle\Entity\User", inversedBy="applications")
     */
    protected $user;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $modality;

    /**
     * @ORM\Column(type="smallint")
     */
    protected $type = self::TYPE_DIGITAL;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $nightStay;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $completed = false;

    /**
     * @ORM\OneToOne(targetEntity="GJA\GameJam\UserBundle\Entity\Order", cascade={"persist"}, inversedBy="compoApplication")
     * @ORM\JoinColumn(onDelete="CASCADE")
     *
     * @var Order
     */
    protected $order;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lockTime = null;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $assisted = false;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $dni;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $additionalData;

    protected $edit;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $ticketSent = false;

    public function __construct()
    {
        $this->date = new \DateTime('now');
    }

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
     * @param mixed $modality
     */
    public function setModality($modality)
    {
        $this->modality = $modality;
    }

    /**
     * @return mixed
     */
    public function getModality()
    {
        return $this->modality;
    }

    /**
     * @param mixed $nightStay
     */
    public function setNightStay($nightStay)
    {
        $this->nightStay = $nightStay;
    }

    /**
     * @return mixed
     */
    public function getNightStay()
    {
        return $this->nightStay;
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

    public static function getAvailableModalitites()
    {
        return [
            self::MODALITY_NORMAL => 'Participar en el concurso',
            self::MODALITY_OUT_OF_COMPO => 'Participar fuera de concurso',
            self::MODALITY_FREE => 'Participar de forma libre',
        ];
    }

    public static function getAvailableTypes()
    {
        return [
            self::TYPE_DIGITAL => 'Videojuego',
            self::TYPE_BOARD_GAME => 'Juego de mesa',
        ];
    }

    public function getModalityAsString()
    {
        return self::getAvailableModalitites()[$this->modality];
    }

    public function getTypeAsString()
    {
        return self::getAvailableTypes()[$this->type];
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
     * @param mixed $completed
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;
    }

    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function isOrderPending()
    {
        if ($order = $this->getOrder()) {
            if (!$order->isPaid()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $edit
     */
    public function setEdit($edit)
    {
        $this->edit = $edit;
    }

    /**
     * @return mixed
     */
    public function getEdit()
    {
        return $this->edit;
    }

    public function isInProgress()
    {
        if (is_null($this->getLockTime())) {
            return false;
        }

        $now = new \DateTime('now');
        $lockTime = clone $this->getLockTime();

        return ($lockTime->add(new \DateInterval(self::LOCK_TTL)) > $now);
    }

    /**
     * @param mixed $lockTime
     */
    public function setLockTime($lockTime)
    {
        $this->lockTime = $lockTime;
    }

    /**
     * @return \DateTime
     */
    public function getLockTime()
    {
        return $this->lockTime;
    }

    /**
     * @param mixed $assisted
     */
    public function setAssisted($assisted)
    {
        $this->assisted = $assisted;
    }

    /**
     * @return mixed
     */
    public function getAssisted()
    {
        return $this->assisted;
    }

    /**
     * @param mixed $additionalData
     */
    public function setAdditionalData($additionalData)
    {
        $this->additionalData = $additionalData;
    }

    /**
     * @return mixed
     */
    public function getAdditionalData()
    {
        return $this->additionalData;
    }

    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
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

    public function isTicketSent()
    {
        return $this->ticketSent;
    }

    public function markTicketAsSent()
    {
        $this->ticketSent = true;
    }
}
