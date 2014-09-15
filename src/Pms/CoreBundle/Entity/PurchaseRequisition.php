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

//    /**
//     * @var ArrayCollection
//     *
//     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\Invoice", mappedBy="purchaseRequisition", cascade={"persist", "remove"})
//     */
//    private $receivedItem;

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

//    /**
//     * @return \Doctrine\Common\Collections\ArrayCollection
//     */
//    public function getReceivedItem()
//    {
//        return $this->receivedItem;
//    }

    public function getDateOfRequisitionText() {
        if(empty($this->dateOfRequisition)){
            return "";
        }

        return $this->getDateOfRequisition()->format('Y-m-d');
    }

    public function setDateOfRequisitionText($date = "") {

        if(!empty($date)){
            return $this->setDateOfRequisition(new \DateTime($date));
        }

        return $this;
    }
}