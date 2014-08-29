<?php

namespace Pms\CoreBundle\Entity;

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
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", inversedBy="purchaseRequisitionItem", cascade={"persist", "remove"})
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
     * @ORM\Column(name="status_po", type="integer", nullable=true)
     */
    private $statusPo;

    public function __toString()
    {
        return $this->getItem();
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
     * Set statusPo
     *
     * @param integer $statusPo
     * @return PurchaseRequisitionItem
     */
    public function setStatusPo($statusPo)
    {
        $this->statusPo = $statusPo;

        return $this;
    }

    /**
     * Get statusPo
     *
     * @return integer
     */
    public function getStatusPo()
    {
        return $this->statusPo;
    }
}