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
     * @var integer
     *
     * @ORM\Column(name="approve_status", type="integer", nullable=true)
     */
    private $approveStatus;

    /**
     * @var Vendor
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Vendor", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="vendors", nullable=true)
     */
    private $vendor;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="buyers", nullable=true)
     */
    private $buyer;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
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

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_one", nullable=true)
     */
    private $approvedOne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_one_date", type="datetime", nullable=true)
     */
    private $approvedOneDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_two", nullable=true)
     */
    private $approvedTwo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_two_date", type="datetime", nullable=true)
     */
    private $approvedTwoDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_three", nullable=true)
     */
    private $approvedThree;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_three_date", type="datetime", nullable=true)
     */
    private $approvedThreeDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_order_item_quantity", type="integer", nullable=true)
     */
    private $totalOrderItemQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_order_item", type="integer", nullable=true)
     */
    private $totalOrderItem;

//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="total_receive_item", type="integer", nullable=true)
//     */
//    private $totalReceiveItem;

    public function __construct()
    {
        $this->purchaseOrderItems = new ArrayCollection();
    }

    /**
     * Set totalOrderItemQuantity
     *
     * @param integer $totalOrderItemQuantity
     * @return PurchaseOrder
     */
    public function setTotalOrderItemQuantity($totalOrderItemQuantity)
    {
        $this->totalOrderItemQuantity = $totalOrderItemQuantity;

        return $this;
    }

    /**
     * Get totalOrderItemQuantity
     *
     * @return integer
     */
    public function getTotalOrderItemQuantity()
    {
        return $this->totalOrderItemQuantity;
    }

    /**
     * Set totalOrderItem
     *
     * @param integer $totalOrderItem
     * @return PurchaseOrder
     */
    public function setTotalOrderItem($totalOrderItem)
    {
        $this->totalOrderItem = $totalOrderItem;

        return $this;
    }

    /**
     * Get totalOrderItem
     *
     * @return integer
     */
    public function getTotalOrderItem()
    {
        return $this->totalOrderItem;
    }
//
//    /**
//     * Set totalReceiveItem
//     *
//     * @param integer $totalReceiveItem
//     * @return PurchaseOrder
//     */
//    public function setTotalReceiveItem($totalReceiveItem)
//    {
//        $this->totalReceiveItem = $totalReceiveItem;
//
//        return $this;
//    }
//
//    /**
//     * Get totalReceiveItem
//     *
//     * @return integer
//     */
//    public function getTotalReceiveItem()
//    {
//        return $this->totalReceiveItem;
//    }

    public function addPurchaseOrderItem(PurchaseOrderItem $item)
    {
        $item->setPurchaseOrder($this);

        if (!$this->getPurchaseOrderItems()->contains($item)) {
            $this->purchaseOrderItems->add($item);
        }

        return $this;
    }

    public function removePurchaseOrderItem(PurchaseOrderItem $item)
    {
        if ($this->getPurchaseOrderItems()->contains($item)) {
            $this->getPurchaseOrderItems()->removeElement($item);
        }
    }

    public function getPurchaseOrderItems()
    {
        return $this->purchaseOrderItems;
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
     * @param User $buyer
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
     * @return User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
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
     * @return User
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
     * Set approveStatus
     *
     * @param integer $approveStatus
     * @return PurchaseOrder
     */
    public function setApproveStatus($approveStatus)
    {
        $this->approveStatus = $approveStatus;

        return $this;
    }

    /**
     * Get approveStatus
     *
     * @return integer
     */
    public function getApproveStatus()
    {
        return $this->approveStatus;
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
     * @param Vendor $vendor
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
     * @return Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Set approvedOne
     *
     * @param User $approvedOne
     * @return PurchaseOrder
     */
    public function setApprovedOne($approvedOne)
    {
        $this->approvedOne = $approvedOne;

        return $this;
    }

    /**
     * Get approvedOne
     *
     * @return User
     */
    public function getApprovedOne()
    {
        return $this->approvedOne;
    }

    /**
     * Set approvedOneDate
     *
     * @param \DateTime $approvedOneDate
     * @return PurchaseOrder
     */
    public function setApprovedOneDate($approvedOneDate)
    {
        $this->approvedOneDate = $approvedOneDate;

        return $this;
    }

    /**
     * Get approvedOneDate
     *
     * @return \DateTime
     */
    public function getApprovedOneDate()
    {
        return $this->approvedOneDate;
    }


    /**
     * Set approvedTwo
     *
     * @param User $approvedTwo
     * @return PurchaseOrder
     */
    public function setApprovedTwo($approvedTwo)
    {
        $this->approvedTwo = $approvedTwo;

        return $this;
    }

    /**
     * Get approvedTwo
     *
     * @return User
     */
    public function getApprovedTwo()
    {
        return $this->approvedTwo;
    }

    /**
     * Set approvedTwoDate
     *
     * @param \DateTime $approvedTwoDate
     * @return PurchaseOrder
     */
    public function setApprovedTwoDate($approvedTwoDate)
    {
        $this->approvedTwoDate = $approvedTwoDate;

        return $this;
    }

    /**
     * Get approvedTwoDate
     *
     * @return \DateTime
     */
    public function getApprovedTwoDate()
    {
        return $this->approvedTwoDate;
    }

    /**
     * Set approvedThree
     *
     * @param User $approvedThree
     * @return PurchaseOrder
     */
    public function setApprovedThree($approvedThree)
    {
        $this->approvedThree = $approvedThree;

        return $this;
    }

    /**
     * Get approvedThree
     *
     * @return User
     */
    public function getApprovedThree()
    {
        return $this->approvedThree;
    }

    /**
     * Set approvedThreeDate
     *
     * @param \DateTime $approvedThreeDate
     * @return PurchaseOrder
     */
    public function setApprovedThreeDate($approvedThreeDate)
    {
        $this->approvedThreeDate = $approvedThreeDate;

        return $this;
    }

    /**
     * Get approvedThreeDate
     *
     * @return \DateTime
     */
    public function getApprovedThreeDate()
    {
        return $this->approvedThreeDate;
    }
}
