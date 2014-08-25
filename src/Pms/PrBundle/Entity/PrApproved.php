<?php

namespace Pms\PrBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrApproved
 *
 * @ORM\Table(name="pr_approved")
 * @ORM\Entity
 */
class PrApproved
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
