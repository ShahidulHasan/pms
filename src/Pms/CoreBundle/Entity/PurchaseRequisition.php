<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Pms\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints\DateTime;

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
     * @ORM\Column(name="date_of_requisitions", type="datetime", nullable=true)
     */
    private $dateOfRequisition;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="closed_date", type="date", nullable=true)
     */
    private $closedDate;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_final_delivered", type="date", nullable=true)
     */
    private $dateOfFinalDelivered;

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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", nullable=true)
     */
    private $createdBy;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="closed_by", nullable=true)
     */
    private $closedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     */
    private $createdDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", nullable=true)
     */
    private $updatedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime", nullable=true)
     */
    private $updatedDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_by_project_head", nullable=true)
     */
    private $approvedByProjectHead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_project_head", type="datetime", nullable=true)
     */
    private $approvedDateProjectHead;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_by_category_head_one", nullable=true)
     */
    private $approvedByCategoryHeadOne;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_head_one", type="datetime", nullable=true)
     */
    private $approvedDateCategoryHeadOne;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_by_category_head_two", nullable=true)
     */
    private $approvedByCategoryHeadTwo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_head_two", type="datetime", nullable=true)
     */
    private $approvedDateCategoryHeadTwo;

    /**
     * @var integer
     *
     * @ORM\Column(name="approve_status", type="integer", nullable=true)
     */
    private $approveStatus;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_requisition_item_quantity", type="integer", nullable=true)
     */
    private $totalRequisitionItemQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_receive_item_quantity", type="integer", nullable=true)
     */
    private $totalReceiveItemQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_order_item_quantity", type="integer", nullable=true)
     */
    private $totalOrderItemQuantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_requisition_item", type="integer", nullable=true)
     */
    private $totalRequisitionItem;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_requisition_item_claimed", type="integer", nullable=true)
     */
    private $totalRequisitionItemClaimed;

    public function __construct()
    {
        $this->purchaseRequisitionItems = new ArrayCollection();
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisition
     */
    public function addPurchaseRequisition($purchaseRequisition)
    {
        if (!$this->getPurchaseRequisitionItems()->contains($purchaseRequisition)) {
            $purchaseRequisition->setPurchaseRequisition($this);
            $this->getPurchaseRequisitionItems()->add($purchaseRequisition);
        }
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisition
     */
    public function removePurchaseRequisition($purchaseRequisition)
    {
        if ($this->getPurchaseRequisitionItems()->contains($purchaseRequisition)) {
            $this->getPurchaseRequisitionItems()->removeElement($purchaseRequisition);
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
     * Set totalRequisitionItemQuantity
     *
     * @param integer $totalRequisitionItemQuantity
     * @return PurchaseRequisition
     */
    public function setTotalRequisitionItemQuantity($totalRequisitionItemQuantity)
    {
        $this->totalRequisitionItemQuantity = $totalRequisitionItemQuantity;

        return $this;
    }

    /**
     * Get totalRequisitionItemQuantity
     *
     * @return integer
     */
    public function getTotalRequisitionItemQuantity()
    {
        return $this->totalRequisitionItemQuantity;
    }

    /**
     * Set totalReceiveItemQuantity
     *
     * @param integer $totalReceiveItemQuantity
     * @return PurchaseRequisition
     */
    public function setTotalReceiveItemQuantity($totalReceiveItemQuantity)
    {
        $this->totalReceiveItemQuantity = $totalReceiveItemQuantity;

        return $this;
    }

    /**
     * Get totalReceiveItemQuantity
     *
     * @return integer
     */
    public function getTotalReceiveItemQuantity()
    {
        return $this->totalReceiveItemQuantity;
    }

    /**
     * Set totalOrderItemQuantity
     *
     * @param integer $totalOrderItemQuantity
     * @return PurchaseRequisition
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
     * Set totalRequisitionItem
     *
     * @param integer $totalRequisitionItem
     * @return PurchaseRequisition
     */
    public function setTotalRequisitionItem($totalRequisitionItem)
    {
        $this->totalRequisitionItem = $totalRequisitionItem;

        return $this;
    }

    /**
     * Get totalRequisitionItem
     *
     * @return integer
     */
    public function getTotalRequisitionItem()
    {
        return $this->totalRequisitionItem;
    }

    /**
     * Set totalRequisitionItemClaimed
     *
     * @param integer $totalRequisitionItemClaimed
     * @return PurchaseRequisition
     */
    public function setTotalRequisitionItemClaimed($totalRequisitionItemClaimed)
    {
        $this->totalRequisitionItemClaimed = $totalRequisitionItemClaimed;

        return $this;
    }

    /**
     * Get totalRequisitionItemClaimed
     *
     * @return integer
     */
    public function getTotalRequisitionItemClaimed()
    {
        return $this->totalRequisitionItemClaimed;
    }

    /**
     * Set project
     *
     * @param Project $project
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
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set updatedBy
     *
     * @param User $updatedBy
     * @return PurchaseRequisition
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     * @return PurchaseRequisition
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set closedBy
     *
     * @param User $closedBy
     * @return PurchaseRequisition
     */
    public function setClosedBy($closedBy)
    {
        $this->closedBy = $closedBy;

        return $this;
    }

    /**
     * Get closedBy
     *
     * @return User
     */
    public function getClosedBy()
    {
        return $this->closedBy;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
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
     * @param User $approvedByProjectHead
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
     * @return User
     */
    public function getApprovedByProjectHead()
    {
        return $this->approvedByProjectHead;
    }

    /**
     * Set closedDate
     *
     * @param /DateTime $closedDate
     * @return PurchaseRequisition
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;

        return $this;
    }

    /**
     * Get closedDate
     *
     * @return /DateTime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set approveStatus
     *
     * @param integer $approveStatus
     * @return PurchaseRequisition
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
     * @param User $approvedByCategoryHeadOne
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
     * @return User
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
     * @param User $approvedByCategoryHeadTwo
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
     * @return User
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

    public function addPurchaseRequisitionItem(PurchaseRequisitionItem $item)
    {
        $item->setPurchaseRequisition($this);
        $this->purchaseRequisitionItems[] = $item;

        return $this;
    }

    public function removePurchaseRequisitionItem(PurchaseRequisitionItem $item)
    {
        $this->purchaseRequisitionItems->removeElement($item);
    }

    public function getPurchaseRequisitionItems()
    {
        return $this->purchaseRequisitionItems;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    public function getDateOfRequisitionText()
    {
        if(empty($this->dateOfRequisition)){
            return "";
        }

        return $this->getDateOfRequisition()->format('Y-m-d');
    }

    public function setDateOfRequisitionText($date = "")
    {
        if(!empty($date)){
            return $this->setDateOfRequisition(new \DateTime($date));
        }

        return $this;
    }
}