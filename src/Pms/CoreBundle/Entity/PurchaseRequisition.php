<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseRequisition
 *
 * @ORM\Table(name="purchase_requisition")
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
     * @ORM\Column(name="approved_by_project_manager", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="approved_by_category_manager", type="string", length=255, nullable=true)
     */
    private $approvedByCategoryManager;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date_category_manager", type="datetime", nullable=true)
     */
    private $approvedDateCategoryManager;

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
     * Set approvedByProjectManager
     *
     * @param integer $approvedByProjectManager
     * @return PurchaseRequisition
     */
    public function setApprovedByProjectManager($approvedByProjectManager)
    {
        $this->approvedByProjectManager = $approvedByProjectManager;

        return $this;
    }

    /**
     * Get approvedByProjectManager
     *
     * @return integer
     */
    public function getApprovedByProjectManager()
    {
        return $this->approvedByProjectManager;
    }

    /**
     * Set approvedDateProjectManager
     *
     * @param \DateTime $approvedDateProjectManager
     * @return PurchaseRequisition
     */
    public function setApprovedDateProjectManager($approvedDateProjectManager)
    {
        $this->approvedByProjectHead = $approvedDateProjectManager;

        return $this;
    }

    /**
     * Get approvedDateProjectManager
     *
     * @return \DateTime
     */
    public function getApprovedDateProjectManager()
    {
        return $this->approvedDateProjectManager;
    }

    /**
     * Set approvedByCategoryManager
     *
     * @param integer $approvedByCategoryManager
     * @return PurchaseRequisition
     */
    public function setApprovedByCategoryManager($approvedByCategoryManager)
    {
        $this->approvedByCategoryManager = $approvedByCategoryManager;

        return $this;
    }

    /**
     * Get approvedByCategoryManager
     *
     * @return integer
     */
    public function getApprovedByCategoryManager()
    {
        return $this->approvedByCategoryManager;
    }

    /**
     * Set approvedDateCategoryManager
     *
     * @param \DateTime $approvedDateCategoryManager
     * @return PurchaseRequisition
     */
    public function setApprovedDateCategoryManager($approvedDateCategoryManager)
    {
        $this->approvedDateCategoryManager = $approvedDateCategoryManager;

        return $this;
    }

    /**
     * Get approvedDateCategoryManager
     *
     * @return \DateTime
     */
    public function getApprovedDateCategoryManager()
    {
        return $this->approvedDateCategoryManager;
    }
}
