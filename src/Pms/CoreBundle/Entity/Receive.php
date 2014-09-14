<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var string
     *
     * @ORM\Column(name="received_by", type="string", length=255)
     */
    private $receivedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="received_date", type="datetime")
     */
    private $receivedDate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ReceivedItem", mappedBy="receive", cascade={"persist", "remove"})
     */
    private $receiveItems;

    public function __construct()
    {
        $this->receiveItems = new ArrayCollection();
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
     * @param string $receivedBy
     * @return ReceivedItem
     */
    public function setReceivedBy($receivedBy)
    {
        $this->receivedBy = $receivedBy;

        return $this;
    }

    /**
     * Get receivedBy
     *
     * @return string
     */
    public function getReceivedBy()
    {
        return $this->receivedBy;
    }

    /**
     * Set receivedDate
     *
     * @param \DateTime $receivedDate
     * @return ReceivedItem
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
