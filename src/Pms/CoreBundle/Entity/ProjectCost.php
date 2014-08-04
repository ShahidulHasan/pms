<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectCost
 *
 * @ORM\Table(name="project_cost")
 * @ORM\Entity(repositoryClass="Pms\UserBundle\Entity\UserRepository")
 */
class ProjectCost
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
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_cost", type="date")
     */
    private $dateOfCost;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="projectCost")
     * @ORM\JoinColumn(name="project")
     */
    private $project;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="projectCost")
     * @ORM\JoinColumn(name="item")
     */
    private $item;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit_price", type="integer")
     */
    private $unitPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="line_total", type="integer")
     */
    private $lineTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=255)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(name="approved_by", type="string", length=255, nullable=true)
     */
    private $approvedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="approved_date", type="datetime", nullable=true)
     */
    private $approvedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="string", length=255)
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="gsn", type="string", length=255)
     */
    private $gsn;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Category", inversedBy="projectCost")
     * @ORM\JoinColumn(name="category")
     */
    private $category;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\SubCategory", inversedBy="projectCost")
     * @ORM\JoinColumn(name="sub_category")
     */
    private $subCategory;

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
     * Set dateOfCost
     *
     * @param /DateTime $dateOfCost
     * @return ProjectCost
     */
    public function setDateOfCost($dateOfCost)
    {
        $this->dateOfCost = $dateOfCost;

        return $this;
    }

    /**
     * Get dateOfCost
     *
     * @return /DateTime
     */
    public function getDateOfCost()
    {
        return $this->dateOfCost;
    }

    /**
     * Set project
     *
     * @param integer $project
     * @return ProjectCost
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
     * Set item
     *
     * @param integer $item
     * @return ProjectCost
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
     * Set quantity
     *
     * @param integer $quantity
     * @return ProjectCost
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
     * Set unitPrice
     *
     * @param integer $unitPrice
     * @return ProjectCost
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return integer 
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set lineTotal
     *
     * @param integer $lineTotal
     * @return ProjectCost
     */
    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = $lineTotal;

        return $this;
    }

    /**
     * Get lineTotal
     *
     * @return integer 
     */
    public function getLineTotal()
    {
        return $this->lineTotal;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return ProjectCost
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return ProjectCost
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
     * Set approvedBy
     *
     * @param string $approvedBy
     * @return ProjectCost
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return string 
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Set approvedDate
     *
     * @param \DateTime $approvedDate
     * @return ProjectCost
     */
    public function setApprovedDate($approvedDate)
    {
        $this->approvedDate = $approvedDate;

        return $this;
    }

    /**
     * Get approvedDate
     *
     * @return \DateTime 
     */
    public function getApprovedDate()
    {
        return $this->approvedDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ProjectCost
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
     * Set invoice
     *
     * @param string $invoice
     * @return ProjectCost
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return string
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set gsn
     *
     * @param string $gsn
     * @return ProjectCost
     */
    public function setGsn($gsn)
    {
        $this->gsn = $gsn;

        return $this;
    }

    /**
     * Get gsn
     *
     * @return string
     */
    public function getGsn()
    {
        return $this->gsn;
    }

    /**
     * Set category
     *
     * @param integer $category
     * @return ProjectCost
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set subCategory
     *
     * @param integer $subCategory
     * @return ProjectCost
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get subCategory
     *
     * @return integer
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }
}
