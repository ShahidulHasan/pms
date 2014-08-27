<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseRequisitionItem
 *
 * @ORM\Table(name="purchase_requisition_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\PurchaseRequisitionItemRepository")
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
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", inversedBy="purchaseRequisitionItem")
     * @ORM\JoinColumn(name="purchase_requisitions_id")
     */
    private $purchaseRequisitionId;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="purchaseRequisitionItem")
     * @ORM\JoinColumn(name="items")
     */
    private $item;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantities", type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="string")
     */
    private $comment;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_required", type="date")
     */
    private $dateOfRequired;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_po", type="integer")
     */
    private $statusPo;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by_category_head_one", type="string", length=255, nullable=true)
     */
    private $approvedByCategoryHeadOne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_head_one", type="datetime", nullable=true)
     */
    private $approvedDateCategoryHeadOne;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by_category_head_two", type="string", length=255, nullable=true)
     */
    private $approvedByCategoryHeadTwo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_head_two", type="datetime", nullable=true)
     */
    private $approvedDateCategoryHeadTwo;

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
     * @param integer $item
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
     * @return integer
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set purchaseRequisitionId
     *
     * @param integer $purchaseRequisitionId
     * @return PurchaseRequisitionItem
     */
    public function setPurchaseRequisitionId($purchaseRequisitionId)
    {
        $this->purchaseRequisitionId = $purchaseRequisitionId;

        return $this;
    }

    /**
     * Get purchaseRequisitionId
     *
     * @return integer
     */
    public function getPurchaseRequisitionId()
    {
        return $this->purchaseRequisitionId;
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

    /**
     * Set approvedByCategoryHeadOne
     *
     * @param integer $approvedByCategoryHeadOne
     * @return PurchaseRequisition
     */
    public function setApprovedByCategoryHeadOne($approvedByCategoryHeadOne)
    {
        $this->approvedByCategoryHeadOne = $approvedByCategoryHeadOne;

        return $this;
    }

    /**
     * Get approvedByCategoryHeadOne
     *
     * @return integer
     */
    public function getApprovedByCategoryHeadOne()
    {
        return $this->approvedByCategoryHeadOne;
    }

    /**
     * Set approvedDateCategoryHeadOne
     *
     * @param \DateTime $approvedDateCategoryHeadOne
     * @return PurchaseRequisition
     */
    public function setApprovedDateCategoryHeadOne($approvedDateCategoryHeadOne)
    {
        $this->approvedDateCategoryHeadOne = $approvedDateCategoryHeadOne;

        return $this;
    }

    /**
     * Get approvedDateCategoryHeadOne
     *
     * @return \DateTime
     */
    public function getApprovedDateCategoryHeadOne()
    {
        return $this->approvedDateCategoryHeadOne;
    }

    /**
     * Set approvedByCategoryHeadTwo
     *
     * @param integer $approvedByCategoryHeadTwo
     * @return PurchaseRequisition
     */
    public function setApprovedByCategoryHeadTwo($approvedByCategoryHeadTwo)
    {
        $this->approvedByCategoryHeadTwo = $approvedByCategoryHeadTwo;

        return $this;
    }

    /**
     * Get approvedByCategoryHeadTwo
     *
     * @return integer
     */
    public function getApprovedByCategoryHeadTwo()
    {
        return $this->approvedByCategoryHeadTwo;
    }

    /**
     * Set approvedDateCategoryHeadTwo
     *
     * @param \DateTime $approvedDateCategoryHeadTwo
     * @return PurchaseRequisition
     */
    public function setApprovedDateCategoryHeadTwo($approvedDateCategoryHeadTwo)
    {
        $this->approvedDateCategoryHeadTwo = $approvedDateCategoryHeadTwo;

        return $this;
    }

    /**
     * Get approvedDateCategoryHeadTwo
     *
     * @return \DateTime
     */
    public function getApprovedDateCategoryHeadTwo()
    {
        return $this->approvedDateCategoryHeadTwo;
    }
}
