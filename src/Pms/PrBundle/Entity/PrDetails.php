<?php

namespace Pms\PrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrDetails
 *
 * @ORM\Table(name="pr_details")
 * @ORM\Entity
 */
class PrDetails
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
