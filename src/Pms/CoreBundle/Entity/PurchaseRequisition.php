<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseRequisition
 *
 * @ORM\Table(name="purchase_requisitions")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\PurchaseRequisitionRepository")
 */
class PurchaseRequisition
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
     * @var integer
     *
     * @ORM\Column(name="requisitions_no", type="integer", nullable=true)
     */
    private $requisitionNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_requisitions", type="date", nullable=true)
     */
    private $dateOfRequisition;

    /**
     * @var integer
     *
     * @ORM\Column(name="claimed_by", type="integer", nullable=true)
     */
    private $claimedBy;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_closings", type="date", nullable=true)
     */
    private $dateOfClosing;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_final_delivered", type="date", nullable=true)
     */
    private $dateOfFinalDelivered;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_claimed", type="date", nullable=true)
     */
    private $dateOfClaimed;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisitionItem", mappedBy="purchaseRequisition", cascade={"persist", "remove"})
     */
    private $purchaseRequisitionItems;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\Invoice", mappedBy="purchaseRequisition", cascade={"persist", "remove"})
     */
    private $invoice;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="purchaseRequisition")
     * @ORM\JoinColumn(name="projects", nullable=true)
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by_project_head", type="integer", length=255, nullable=true)
     */
    private $approvedByProjectHead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_project_head", type="datetime", nullable=true)
     */
    private $approvedDateProjectHead;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by_category_head_one", type="integer", length=255, nullable=true)
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
     * @ORM\Column(name="approved_by_category_head_two", type="integer", length=255, nullable=true)
     */
    private $approvedByCategoryHeadTwo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_head_two", type="datetime", nullable=true)
     */
    private $approvedDateCategoryHeadTwo;

    public function __construct()
    {
        $this->purchaseRequisitionItem = new ArrayCollection();
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisition
     */
    public function addPurchaseRequisition($purchaseRequisition)
    {
        if (!$this->getPurchaseRequisitionItem()->contains($purchaseRequisition)) {
            $purchaseRequisition->setPurchaseRequisition($this);
            $this->getPurchaseRequisitionItem()->add($purchaseRequisition);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getPurchaseRequisitionItem()
    {
        return $this->purchaseRequisitionItem;
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisition
     */
    public function removePurchaseRequisition($purchaseRequisition)
    {
        if ($this->getPurchaseRequisitionItem()->contains($purchaseRequisition)) {
            $this->getPurchaseRequisitionItem()->removeElement($purchaseRequisition);
        }
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
     * Set requisitionNo
     *
     * @param integer $requisitionNo
     * @return PurchaseRequisition
     */
    public function setRequisitionNo($requisitionNo)
    {
        $this->requisitionNo = $requisitionNo;

        return $this;
    }

    /**
     * Get requisitionNo
     *
     * @return integer
     */
    public function getRequisitionNo()
    {
        return $this->requisitionNo;
    }

    /**
     * Set dateOfRequisition
     *
     * @param /DateTime $dateOfRequisition
     * @return PurchaseRequisition
     */
    public function setDateOfRequisition($dateOfRequisition)
    {
        $this->dateOfRequisition = $dateOfRequisition;

        return $this;
    }

    /**
     * Get dateOfRequisition
     *
     * @return /DateTime
     */
    public function getDateOfRequisition()
    {
        return $this->dateOfRequisition;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return PurchaseRequisition
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
     * @param integer $project
     * @return PurchaseRequisition
     */
    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return integer
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return PurchaseRequisition
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
     * @return PurchaseRequisition
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
     * Set approvedByProjectHead
     *
     * @param integer $approvedByProjectHead
     * @return PurchaseRequisition
     */
    public function setApprovedByProjectHead($approvedByProjectHead)
    {
        $this->approvedByProjectHead = $approvedByProjectHead;

        return $this;
    }

    /**
     * Get approvedByProjectHead
     *
     * @return integer
     */
    public function getApprovedByProjectHead()
    {
        return $this->approvedByProjectHead;
    }

    /**
     * Set claimedBy
     *
     * @param integer $claimedBy
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
     * @return integer
     */
    public function getClaimedBy()
    {
        return $this->claimedBy;
    }

    /**
     * Set dateOfClosing
     *
     * @param /DateTime $dateOfClosing
     * @return PurchaseRequisition
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
     * Set dateOfFinalDelivered
     *
     * @param /DateTime $dateOfFinalDelivered
     * @return PurchaseRequisition
     */
    public function setDateOfFinalDelivered($dateOfFinalDelivered)
    {
        $this->dateOfFinalDelivered = $dateOfFinalDelivered;

        return $this;
    }

    /**
     * Get dateOfFinalDelivered
     *
     * @return /DateTime
     */
    public function getDateOfFinalDelivered()
    {
        return $this->dateOfFinalDelivered;
    }

    /**
     * Set dateOfClaimed
     *
     * @param /DateTime $dateOfClaimed
     * @return PurchaseRequisition
     */
    public function setDateOfClaimed($dateOfClaimed)
    {
        $this->dateOfClaimed = $dateOfClaimed;

        return $this;
    }

    /**
     * Get dateOfClaimed
     *
     * @return /DateTime
     */
    public function getDateOfClaimed()
    {
        return $this->dateOfClaimed;
    }

    /**
     * Set approvedDateProjectHead
     *
     * @param \DateTime $approvedDateProjectHead
     * @return PurchaseRequisition
     */
    public function setApprovedDateProjectHead($approvedDateProjectHead)
    {
        $this->approvedDateProjectHead = $approvedDateProjectHead;

        return $this;
    }

    /**
     * Get approvedDateProjectHead
     *
     * @return \DateTime
     */
    public function getApprovedDateProjectHead()
    {
        return $this->approvedDateProjectHead;
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

    function setPurchaseRequisitionItems(Collection $items)
    {
        $this->purchaseRequisitionItems = $items;

        return $this;
    }

    public function addPurchaseRequisitionItem(PurchaseRequisitionItem $item)
    {
        $this->purchaseRequisitionItems[] = $item;

        return $this;
    }

    public function removePurchaseRequisitionItem(PurchaseRequisitionItem $item)
    {
        $this->purchaseRequisitionItems->removeElement($item);
    }

    public function getPurchaseRequisitionItems()
    {
        return $this->purchaseRequisitionItem;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInvoice()
    {
        return $this->invoice;
    }
}