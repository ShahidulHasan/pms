<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Pms\UserBundle\Entity\User;

/**
 * PurchaseOrder
 *
 * @ORM\Table(name="purchase_orders")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\PurchaseOrderRepository")
 */
class PurchaseOrder
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseOrderItem", mappedBy="purchaseOrder", cascade={"persist", "remove"})
     */
    private $purchaseOrderItems;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_no", type="integer", nullable=true)
     */
    private $orderNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="po_nonpo", type="integer", nullable=true)
     */
    private $poNonpo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var Vendor
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Vendor", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="vendors", nullable=true)
     */
    private $vendor;

    /**
     * @var Buyer
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Buyer", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="buyers", nullable=true)
     */
    private $buyer;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="created_by", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_closings", type="date", nullable=true)
     */
    private $dateOfClosing;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_delivered", type="date", nullable=true)
     */
    private $dateOfDelivered;

    public function __construct()
    {
        $this->purchaseOrderItem = new ArrayCollection();
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseOrderItem $purchaseOrder
     */
    public function addPurchaseOrder($purchaseOrder)
    {
        if (!$this->getPurchaseOrderItem()->contains($purchaseOrder)) {
            $purchaseOrder->setPurchaseOrder($this);
            $this->getPurchaseOrderItem()->add($purchaseOrder);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getPurchaseOrderItem()
    {
        return $this->purchaseOrderItem;
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseOrderItem $purchaseOrder
     */
    public function removePurchaseOrder($purchaseOrder)
    {
        if ($this->getPurchaseOrderItem()->contains($purchaseOrder)) {
            $this->getPurchaseOrderItem()->removeElement($purchaseOrder);
        }
    }

    function setPurchaseOrderItems(Collection $items)
    {
        $this->purchaseOrderItems = $items;

        return $this;
    }

    public function addPurchaseOrderItem(PurchaseOrderItem $item)
    {
        $this->purchaseOrderItems[] = $item;

        return $this;
    }

    public function removePurchaseOrderItem(PurchaseOrderItem $item)
    {
        $this->purchaseOrderItems->removeElement($item);
    }

    public function getPurchaseOrderItems()
    {
        return $this->purchaseOrderItem;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderNo
     *
     * @param integer $orderNo
     * @return PurchaseOrder
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    /**
     * Get orderNo
     *
     * @return integer
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Set buyer
     *
     * @param integer $buyer
     * @return PurchaseOrder
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return integer
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return PurchaseOrder
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return PurchaseOrder
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set dateOfDelivered
     *
     * @param /DateTime $dateOfDelivered
     * @return PurchaseOrder
     */
    public function setDateOfDelivered($dateOfDelivered)
    {
        $this->dateOfDelivered = $dateOfDelivered;

        return $this;
    }

    /**
     * Get dateOfDelivered
     *
     * @return /DateTime
     */
    public function getDateOfDelivered()
    {
        return $this->dateOfDelivered;
    }

    /**
     * Set dateOfClosing
     *
     * @param /DateTime $dateOfClosing
     * @return PurchaseOrder
     */
    public function setDateOfClosing($dateOfClosing)
    {
        $this->dateOfClosing = $dateOfClosing;

        return $this;
    }

    /**
     * Get dateOfClosing
     *
     * @return /DateTime
     */
    public function getDateOfClosing()
    {
        return $this->dateOfClosing;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return PurchaseOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set poNonpo
     *
     * @param integer $poNonpo
     * @return PurchaseOrder
     */
    public function setPoNonpo($poNonpo)
    {
        $this->poNonpo = $poNonpo;

        return $this;
    }

    /**
     * Get poNonpo
     *
     * @return integer
     */
    public function getPoNonpo()
    {
        return $this->poNonpo;
    }

    /**
     * Set vendor
     *
     * @param integer $vendor
     * @return PurchaseOrder
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return integer
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}
