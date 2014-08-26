<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseOrderItem
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\PurchaseOrderItemRepository")
 */
class PurchaseOrderItem
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
