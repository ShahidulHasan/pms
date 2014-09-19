<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ProjectCostItem
 *
 * @ORM\Table(name="project_cost_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\ProjectCostItemRepository")
 */
class ProjectCostItem
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
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="projectCostItem")
     * @ORM\JoinColumn(name="projects")
     */
    private $project;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="projectCostItem")
     * @ORM\JoinColumn(name="items")
     */
    private $item;

    /**
     * @var Buyer
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Buyer", inversedBy="projectCostItem")
     * @ORM\JoinColumn(name="buyers", nullable=true)
     */
    private $buyer;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Category", inversedBy="projectCostItem")
     * @ORM\JoinColumn(name="categories", nullable=true)
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantities", type="integer")
     */
    private $quantity;

    /**
     * @var decimal
     *
     * @ORM\Column(name="unit_prices", type="decimal")
     */
    private $unitPrice;

    /**
     * @var decimal
     *
     * @ORM\Column(name="line_totals", type="decimal")
     */
    private $lineTotal;

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
     * @ORM\Column(name="sub_categories", type="string", length=255, nullable=true)
     */
    private $subCategory;

    /**
     * @var integer
     *
     * @ORM\Column(name="invoice", type="integer", length=255, nullable=true)
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="grn", type="string", length=255, nullable=true)
     */
    private $grn;

    /**
     * @var string
     *
     * @ORM\Column(name="pr", type="string", length=255, nullable=true)
     */
    private $pr;

    /**
     * @var string
     *
     * @ORM\Column(name="po", type="string", length=255, nullable=true)
     */
    private $po;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=255, nullable=true)
     */
    private $comment;

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
     * @return ProjectCostItem
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
     * @return ProjectCostItem
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
     * Set category
     *
     * @param integer $category
     * @return ProjectCostItem
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
     * Set item
     *
     * @param integer $item
     * @return ProjectCostItem
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
     * Set buyer
     *
     * @param Buyer $buyer
     * @return ProjectCostItem
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return Buyer
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return ProjectCostItem
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
     * Set subCategory
     *
     * @param string $subCategory
     * @return ProjectCostItem
     */
    public function setSubCategory($subCategory)
    {
        $this->subCategory = $subCategory;

        return $this;
    }

    /**
     * Get subCategory
     *
     * @return string
     */
    public function getSubCategory()
    {
        return $this->subCategory;
    }

    /**
     * Set unitPrice
     *
     * @param decimal $unitPrice
     * @return ProjectCostItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return decimal
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set lineTotal
     *
     * @param decimal $lineTotal
     * @return ProjectCostItem
     */
    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = $lineTotal;

        return $this;
    }

    /**
     * Get lineTotal
     *
     * @return decimal
     */
    public function getLineTotal()
    {
        return $this->lineTotal;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return ProjectCostItem
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
     * @return ProjectCostItem
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
     * @param integer $approvedBy
     * @return ProjectCostItem
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return integer
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * Set approvedDate
     *
     * @param \DateTime $approvedDate
     * @return ProjectCostItem
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
     * @return ProjectCostItem
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
     * @param integer $invoice
     * @return ProjectCostItem
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return integer
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set grn
     *
     * @param string $grn
     * @return ProjectCostItem
     */
    public function setGrn($grn)
    {
        $this->grn = $grn;

        return $this;
    }

    /**
     * Get grn
     *
     * @return string
     */
    public function getGrn()
    {
        return $this->grn;
    }

    /**
     * Set pr
     *
     * @param string $pr
     * @return ProjectCostItem
     */
    public function setPr($pr)
    {
        $this->pr = $pr;

        return $this;
    }

    /**
     * Get pr
     *
     * @return string
     */
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * Set po
     *
     * @param string $po
     * @return ProjectCostItem
     */
    public function setPo($po)
    {
        $this->po = $po;

        return $this;
    }

    /**
     * Get po
     *
     * @return string
     */
    public function getPo()
    {
        return $this->po;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return ProjectCostItem
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
}
