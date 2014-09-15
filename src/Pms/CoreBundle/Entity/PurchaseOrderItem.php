<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseOrderItem
 *
 * @ORM\Table(name="purchase_order_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\PurchaseOrderItemRepository")
 */
class PurchaseOrderItem
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
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="purchaseOrderItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="items", nullable=true)
     */
    private $item;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="purchaseOrderItem")
     * @ORM\JoinColumn(name="projects", nullable=true)
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantities", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var PurchaseOrder
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseOrder", inversedBy="purchaseOrderItems", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="purchase_orders", nullable=true)
     */
    private $purchaseOrder;

    /**
     * @var PurchaseRequisitionItem
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisitionItem")
     * @ORM\JoinColumn(name="purchase_requisition_item", nullable=true)
     */
    private $purchaseRequisitionItem;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string", nullable=true)
     */
    private $comment;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

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
     * Set quantity
     *
     * @param integer $quantity
     * @return PurchaseOrderItem
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
     * Set purchaseOrder
     *
     * @param PurchaseOrder $purchaseOrder
     * @return PurchaseOrderItem
     */
    public function setPurchaseOrder($purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;

        return $this;
    }

    /**
     * Get purchaseOrder
     *
     * @return PurchaseOrder
     */
    public function getPurchaseOrder()
    {
        return $this->purchaseOrder;
    }

    /**
     * Set item
     *
     * @param Item $item
     * @return PurchaseOrderItem
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
     * Set comment
     *
     * @param string $comment
     * @return PurchaseOrderItem
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
     * @return PurchaseOrderItem
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
     * Set project
     *
     * @param Project $project
     * @return PurchaseOrderItem
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return \Pms\CoreBundle\Entity\PurchaseRequisitionItem
     */
    public function getPurchaseRequisitionItem()
    {
        return $this->purchaseRequisitionItem;
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisitionItem
     * @return $this
     */
    public function setPurchaseRequisitionItem($purchaseRequisitionItem)
    {
        $this->purchaseRequisitionItem = $purchaseRequisitionItem;
        $this->item = $purchaseRequisitionItem->getItem();
        $this->project = $purchaseRequisitionItem->getPurchaseRequisition()->getProject();
        return $this;
    }

    public function getItemName()
    {
        $this->item->getItemName();
    }
}
