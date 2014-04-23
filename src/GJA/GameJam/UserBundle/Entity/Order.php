<?php

/*
 * This file is part of gamejamua.com
 *
 * (c) Alberto Fernández <albertofem@gmail.com>
 *
 * For the full copyright and license information, please read
 * the LICENSE file that was distributed with this source code.
 */

namespace GJA\GameJam\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use GJA\GameJam\CompoBundle\Order\ItemInterface;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;
use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;

/**
 * @ORM\Entity(repositoryClass="GJA\GameJam\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="gamejam_users_orders")
 */
class Order
{
    const PAYPAL_COMMISSION = 0.34;
    const PAYPAL_BASE = 0.35;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction")
     */
    protected $paymentInstruction;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    protected $orderNumber;

    /**
     * @ORM\Column(type="decimal", precision=2, nullable=true)
     */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     *
     * @var User
     */
    protected $user;

    /**
     * @var ItemInterface[]
     */
    protected $items;

    public function __construct(User $user, $orderNumber = null)
    {
        $this->createdAt = new \DateTime("now");
        $this->user = $user;
        $this->orderNumber = $orderNumber ?: $this->generateRandomOrderNumber();
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getAmount()
    {
        if(is_null($this->amount))
        {
            if(is_null($this->items))
                throw new \InvalidArgumentException("This order has no amount!");

            $this->amount = 0;

            foreach($this->items as $item)
            {
                $this->amount += $item->getAmount();
            }
        }

        return $this->amount;
    }

    /**
     * @return PaymentInstruction
     */
    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
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

    public function isPaid()
    {
        if($paymentInstruction = $this->getPaymentInstruction())
        {
            return $paymentInstruction->getState() == PaymentInstructionInterface::STATE_CLOSED ?
                true :
                false;
        }

        return false;
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

    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;
    }

    /**
     * @param \GJA\GameJam\CompoBundle\Order\ItemInterface[] $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @return \GJA\GameJam\CompoBundle\Order\ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }

    public function getPaypalCommission()
    {
        return round($this->amount * self::PAYPAL_COMMISSION, 2, PHP_ROUND_HALF_UP) + self::PAYPAL_BASE;
    }

    public function getPaypalAmount()
    {
        return $this->getPaypalCommission() + $this->getAmount();
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

    private function generateRandomOrderNumber()
    {
        return substr(strtoupper(sha1($this->getUser()->getId() . uniqid())), 1, 8);
    }
} 