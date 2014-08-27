<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseRequisition
 *
 * @ORM\Table(name="purchase_requisitions")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\PurchaseRequisitionRepository")
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
     * @ORM\Column(name="requisitions_no", type="integer")
     */
    private $requisitionNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_requisitions", type="date")
     */
    private $dateOfRequisition;

    /**
     * @var integer
     *
     * @ORM\Column(name="claimed_by", type="integer")
     */
    private $claimedBy;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_closings", type="date")
     */
    private $dateOfClosing;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_final_delivered", type="date")
     */
    private $dateOfFinalDelivered;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_claimed", type="date")
     */
    private $dateOfClaimed;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisitionItem", mappedBy="purchaseRequisitionId")
     */
    private $purchaseRequisitionItem;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="purchaseRequisition")
     * @ORM\JoinColumn(name="projects")
     */
    private $project;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="approved_by_project_head", type="string", length=255, nullable=true)
     */
    private $approvedByProjectHead;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_project_head", type="datetime", nullable=true)
     */
    private $approvedDateProjectHead;

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
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPurchaseRequisitionItem()
    {
        return $this->purchaseRequisitionItem;
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
    public function setApprovedDateProjectManager($approvedDateProjectHead)
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
}
