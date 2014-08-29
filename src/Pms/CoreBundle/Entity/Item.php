<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\ItemRepository")
 */
class Item
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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ProjectCostItem", mappedBy="item")
     */
    private $projectCostItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisitionItem", mappedBy="item")
     */
    private $purchaseRequisitionItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseOrderItem", mappedBy="item")
     */
    private $purchaseOrderItem;

    /**
     * @var string
     *
     * @ORM\Column(name="items_name", type="string", length=255)
     */
    private $itemName;

    /**
     * @var string
     *
     * @ORM\Column(name="item_unit", type="string", length=255)
     */
    private $itemUnit;

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
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

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
     * Set itemName
     *
     * @param string $itemName
     * @return Item
     */
    public function setItemName($itemName)
    {
        $this->itemName = $itemName;

        return $this;
    }

    /**
     * Get itemName
     *
     * @return string 
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * Set itemUnit
     *
     * @param string $itemUnit
     * @return Item
     */
    public function setItemUnit($itemUnit)
    {
        $this->itemUnit = $itemUnit;

        return $this;
    }

    /**
     * Get itemUnit
     *
     * @return string
     */
    public function getItemUnit()
    {
        return $this->itemUnit;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     * @return Item
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
     * @return Item
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
     * Set status
     *
     * @param integer $status
     * @return Item
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
    public function getProjectCostItem()
    {
        return $this->projectCostItem;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPurchaseRequisitionItem()
    {
        return $this->purchaseRequisitionItem;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPurchaseOrderItem()
    {
        return $this->purchaseOrderItem;
    }
}
