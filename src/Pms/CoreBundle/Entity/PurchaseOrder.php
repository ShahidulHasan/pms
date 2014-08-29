<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseOrder
 *
 * @ORM\Table(name="purchase_orders")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\PurchaseOrderRepository")
 */
class PurchaseOrder
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
     * @ORM\Column(name="order_no", type="integer")
     */
    private $orderNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="claimed_by", type="integer")
     */
    private $claimedBy;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_claimed", type="date")
     */
    private $dateOfClaimed;

    /**
     * @var integer
     *
     * @ORM\Column(name="po_nonpo", type="integer")
     */
    private $poNonpo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="vendors", type="integer")
     */
    private $vendor;

    /**
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Project", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="projects")
     */
    private $project;

    /**
     * @var Buyer
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Buyer", inversedBy="purchaseOrder")
     * @ORM\JoinColumn(name="buyers")
     */
    private $buyer;

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
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_closings", type="date")
     */
    private $dateOfClosing;

    /**
     * @var /DateTime
     *
     * @ORM\Column(name="date_of_delivered", type="date")
     */
    private $dateOfDelivered;

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
     * Set orderNo
     *
     * @param integer $orderNo
     * @return PurchaseOrder
     */
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;

        return $this;
    }

    /**
     * Get orderNo
     *
     * @return integer
     */
    public function getOrderNo()
    {
        return $this->orderNo;
    }

    /**
     * Set claimedBy
     *
     * @param integer $claimedBy
     * @return PurchaseOrder
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
     * Set dateOfClaimed
     *
     * @param /DateTime $dateOfClaimed
     * @return PurchaseOrder
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
     * Set project
     *
     * @param integer $project
     * @return PurchaseOrder
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
     * Set buyer
     *
     * @param integer $buyer
     * @return PurchaseOrder
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return integer
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return PurchaseOrder
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
     * @return PurchaseOrder
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
     * Set dateOfDelivered
     *
     * @param /DateTime $dateOfDelivered
     * @return PurchaseOrder
     */
    public function setDateOfDelivered($dateOfDelivered)
    {
        $this->dateOfDelivered = $dateOfDelivered;

        return $this;
    }

    /**
     * Get dateOfDelivered
     *
     * @return /DateTime
     */
    public function getDateOfDelivered()
    {
        return $this->dateOfDelivered;
    }

    /**
     * Set dateOfClosing
     *
     * @param /DateTime $dateOfClosing
     * @return PurchaseOrder
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
     * Set status
     *
     * @param integer $status
     * @return PurchaseOrder
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
     * Set poNonpo
     *
     * @param integer $poNonpo
     * @return PurchaseOrder
     */
    public function setPoNonpo($poNonpo)
    {
        $this->poNonpo = $poNonpo;

        return $this;
    }

    /**
     * Get poNonpo
     *
     * @return integer
     */
    public function getPoNonpo()
    {
        return $this->poNonpo;
    }

    /**
     * Set vendor
     *
     * @param integer $vendor
     * @return PurchaseOrder
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Get vendor
     *
     * @return integer
     */
    public function getVendor()
    {
        return $this->vendor;
    }
}
