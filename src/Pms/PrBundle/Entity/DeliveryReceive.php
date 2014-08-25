<?php

namespace Pms\PrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryReceive
 *
 * @ORM\Table(name="delivery_receive")
 * @ORM\Entity
 */
class DeliveryReceive
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
