<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Pms\UserBundle\Entity\User;

/**
 * PurchaseRequisitionItem
 *
 * @ORM\Table(name="purchase_requisition_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\PurchaseRequisitionItemRepository")
 */
class PurchaseRequisitionItem
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
     * @var PurchaseRequisition
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", inversedBy="purchaseRequisitionItems", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="purchase_requisitions", nullable=true)
     */
    private $purchaseRequisition;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="purchaseRequisitionItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="items", nullable=true)
     */
    private $item;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ReceivedItem", mappedBy="purchaseRequisitionItem", cascade={"persist", "remove"})
     */
    private $receivedItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseOrderItem", mappedBy="purchaseRequisitionItem", cascade={"persist", "remove"})
     */
    private $purchaseOrderItems;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantities", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", nullable=true)
     */
    private $comment;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_required", type="date", nullable=true)
     */
    private $dateOfRequired;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="purchase_order_quantity", type="integer", nullable=true)
     */
    private $purchaseOrderQuantity;


    /**
     * @var integer
     *
     * @ORM\Column(name="received_quantity", type="integer", nullable=true)
     */
    private $receivedQuantity;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="claimed_by", nullable=true)
     */
    private $claimedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="claimed_date", type="datetime", nullable=true)
     */
    private $claimedDate;

    /**
     * Set claimedBy
     *
     * @param User $claimedBy
     * @return PurchaseRequisition
     */
    public function setClaimedBy($claimedBy)
    {
        $this->claimedBy = $claimedBy;

        return $this;
    }

    /**
     * Get claimedBy
     *
     * @return User
     */
    public function getClaimedBy()
    {
        return $this->claimedBy;
    }

    /**
     * Set claimedDate
     *
     * @param \DateTime $claimedDate
     * @return PurchaseRequisition
     */
    public function setClaimedDate($claimedDate)
    {
        $this->claimedDate = $claimedDate;

        return $this;
    }

    /**
     * Get claimedDate
     *
     * @return \DateTime
     */
    public function getClaimedDate()
    {
        return $this->claimedDate;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="po_approval_status", type="integer", nullable=true)
     */
    private $poApprovalStatus;

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReceivedItem()
    {
        return $this->receivedItem;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPurchaseOrderItems()
    {
        return $this->purchaseOrderItems;
    }

    public function __toString()
    {
        return $this->getId() . "";
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
     * Set item
     *
     * @param Item $item
     * @return PurchaseRequisitionItem
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set purchaseRequisition
     *
     * @param PurchaseRequisition $purchaseRequisition
     * @return PurchaseRequisitionItem
     */
    public function setPurchaseRequisition($purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;

        return $this;
    }

    /**
     * Get purchaseRequisition
     *
     * @return PurchaseRequisition
     */
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return PurchaseRequisitionItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set dateOfRequired
     *
     * @param /DateTime $dateOfRequired
     * @return PurchaseRequisitionItem
     */
    public function setDateOfRequired($dateOfRequired)
    {
        $this->dateOfRequired = $dateOfRequired;

        return $this;
    }

    /**
     * Get dateOfRequired
     *
     * @return /DateTime
     */
    public function getDateOfRequired()
    {
        return $this->dateOfRequired;
    }

    public function getDateOfRequiredText() {
        if(empty($this->dateOfRequired)){
            return "";
        }

        return $this->getDateOfRequired()->format('Y-m-d');
    }

    public function setDateOfRequiredText($date = "") {

        if(!empty($date)){
            return $this->setDateOfRequired(new \DateTime($date));
        }

        return $this;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return PurchaseRequisitionItem
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return PurchaseRequisitionItem
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
     * Set poApprovalStatus
     *
     * @param integer $poApprovalStatus
     * @return PurchaseRequisition
     */
    public function setPoApprovalStatus($poApprovalStatus)
    {
        $this->poApprovalStatus = $poApprovalStatus;

        return $this;
    }

    /**
     * Get poApprovalStatus
     *
     * @return integer
     */
    public function getPoApprovalStatus()
    {
        return $this->poApprovalStatus;
    }

    /**
     * Set purchaseOrderQuantity
     *
     * @param integer $purchaseOrderQuantity
     * @return PurchaseRequisitionItem
     */
    public function setPurchaseOrderQuantity($purchaseOrderQuantity)
    {
        $this->purchaseOrderQuantity = $purchaseOrderQuantity;

        return $this;
    }

    /**
     * Get purchaseOrderQuantity
     *
     * @return integer
     */
    public function getPurchaseOrderQuantity()
    {
        return $this->purchaseOrderQuantity;
    }

    /**
     * Set receivedQuantity
     *
     * @param integer $receivedQuantity
     * @return PurchaseRequisitionItem
     */
    public function setReceivedQuantity($receivedQuantity)
    {
        $this->receivedQuantity = $receivedQuantity;

        return $this;
    }

    /**
     * Get receivedQuantity
     *
     * @return integer
     */
    public function getReceivedQuantity()
    {
        return $this->receivedQuantity;
    }

    public function getItemName(){
        $this->getItem()->getItemName();
    }
}