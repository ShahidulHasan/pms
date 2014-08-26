<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReceivedItem
 *
 * @ORM\Table(name="received_items")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\ReceivedItemRepository")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
