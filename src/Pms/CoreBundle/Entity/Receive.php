<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Pms\UserBundle\Entity\User;

/**
 * Receive
 *
 * @ORM\Table(name="receives")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\ReceiveRepository")
 */
class Receive
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="received_by", nullable=true)
     */
    private $receivedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received_date", type="datetime")
     */
    private $receivedDate;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Invoice", inversedBy="receive")
     * @ORM\JoinColumn(name="invoices")
     */
    private $invoice;

    /**
     * @var Invoice
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Invoice")
     * @ORM\JoinColumn(name="calan")
     */
    private $calan;

    /**
     * @var string
     *
     * @ORM\Column(name="grn", type="string", length=255, nullable=true)
     */
    private $grn;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ReceivedItem", mappedBy="receive", cascade={"persist", "remove"})
     */
    private $receiveItems;

    /**
     * @var Vendor
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Vendor", inversedBy="receive")
     * @ORM\JoinColumn(name="vendors", nullable=true)
     */
    private $vendor;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="buyers", nullable=true)
     */
    private $buyer;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

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
     * @ORM\Column(name="closed_date", type="datetime", nullable=true)
     */
    private $closedDate;

    public function __construct()
    {
        $this->receiveItems = new ArrayCollection();
    }

    /**
     * Set closedBy
     *
     * @param User $closedBy
     * @return Receive
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
     * Set closedDate
     *
     * @param \DateTime $closedDate
     * @return Receive
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;

        return $this;
    }

    /**
     * Get closedDate
     *
     * @return \DateTime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Receive
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
     * Set buyer
     *
     * @param User $buyer
     * @return Receive
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set vendor
     *
     * @param integer $vendor
     * @return Receive
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

    public function addReceiveItem(ReceivedItem $item)
    {
        $item->setReceive($this);

        if (!$this->getReceiveItems()->contains($item)) {
            $this->receiveItems->add($item);
        }

        return $this;
    }

    public function removeReceiveItem(ReceivedItem $item)
    {
        if ($this->getReceiveItems()->contains($item)) {
            $this->getReceiveItems()->removeElement($item);
        }
    }

    public function getReceiveItems()
    {
        return $this->receiveItems;
    }

    /**
     * Set invoice
     *
     * @param Invoice $invoice
     * @return Receive
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return Invoice
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set calan
     *
     * @param Invoice $calan
     * @return Receive
     */
    public function setCalan($calan)
    {
        $this->calan = $calan;

        return $this;
    }

    /**
     * Get calan
     *
     * @return Invoice
     */
    public function getCalan()
    {
        return $this->calan;
    }

    /**
     * Set grn
     *
     * @param string $grn
     * @return Receive
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set receivedBy
     *
     * @param User $receivedBy
     * @return Receive
     */
    public function setReceivedBy($receivedBy)
    {
        $this->receivedBy = $receivedBy;

        return $this;
    }

    /**
     * Get receivedBy
     *
     * @return User
     */
    public function getReceivedBy()
    {
        return $this->receivedBy;
    }

    /**
     * Set receivedDate
     *
     * @param \DateTime $receivedDate
     * @return Receive
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;

        return $this;
    }

    /**
     * Get receivedDate
     *
     * @return \DateTime
     */
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }
}
