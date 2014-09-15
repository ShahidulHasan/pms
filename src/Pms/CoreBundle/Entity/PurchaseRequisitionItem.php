<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getReceivedItem()
    {
        return $this->receivedItem;
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
     * @param string $item
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
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set purchaseRequisition
     *
     * @param integer $purchaseRequisition
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
     * @return integer
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

    public function getItemName(){
        $this->getItem()->getItemName();
    }
}