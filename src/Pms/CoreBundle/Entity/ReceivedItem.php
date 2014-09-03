<?php

namespace Pms\CoreBundle\Entity;

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
     * @var PurchaseRequisition
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", inversedBy="receivedItem")
     * @ORM\JoinColumn(name="purchase_requisitions")
     */
    private $purchaseRequisition;

    /**
     * @var Item
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Item", inversedBy="receivedItem", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="received_items", nullable=true)
     */
    private $item;

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
     * Set purchaseRequisition
     *
     * @param integer $purchaseRequisition
     * @return ReceivedItem
     */
    public function setPurchaseRequisition($purchaseRequisition)
    {
        $this->purchaseRequisition = $purchaseRequisition;

        return $this;
    }

    /**
     * Get purchaseRequisition
     *
     * @return integer
     */
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
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
}
