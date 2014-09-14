<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReceivedItem
 *
 * @ORM\Table(name="received_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\ReceivedItemRepository")
 */
class ReceivedItem
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
     * @var string
     *
     * @ORM\Column(name="grn", type="string", length=255, nullable=true)
     */
    private $grn;

    /**
     * @var PurchaseRequisitionItem
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisitionItem")
     * @ORM\JoinColumn(name="purchase_requisition_item", nullable=true)
     */
    private $purchaseRequisitionItem;
    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="receivedItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="items", nullable=true)
     */
    private $item;

    /**
     * @var Receive
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Receive", inversedBy="receiveItems", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="receives", nullable=true)
     */
    private $receive;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantities", type="integer")
     */
    private $quantity;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Invoice", inversedBy="receivedItem")
     * @ORM\JoinColumn(name="invoices")
     */
    private $invoice;

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
     * Set quantity
     *
     * @param integer $quantity
     * @return ReceivedItem
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
     * Set invoice
     *
     * @param integer $invoice
     * @return ReceivedItem
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
     * @return \Pms\CoreBundle\Entity\PurchaseRequisitionItem
     */
    public function getPurchaseRequisitionItem()
    {
        return $this->purchaseRequisitionItem;
    }

    /**
     * @param \Pms\CoreBundle\Entity\PurchaseRequisitionItem $purchaseRequisitionItem
     * @return $this
     */
    public function setPurchaseRequisitionItem($purchaseRequisitionItem)
    {
        $this->purchaseRequisitionItem = $purchaseRequisitionItem;
        $this->item = $purchaseRequisitionItem->getItem();
        return $this;
    }

    /**
     * Set grn
     *
     * @param string $grn
     * @return ReceivedItem
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
     * Set item
     *
     * @param integer $item
     * @return ReceivedItem
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
     * Set receive
     *
     * @param Receive $receive
     * @return ReceivedItem
     */
    public function setReceive($receive)
    {
        $this->receive = $receive;

        return $this;
    }

    /**
     * Get receive
     *
     * @return integer
     */
    public function getReceive()
    {
        return $this->receive;
    }
}
