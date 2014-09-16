<?php

namespace Pms\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Pms\UserBundle\Entity\User;

/**
 * Project
 *
 * @ORM\Table(name="projects")
 * @ORM\Entity(repositoryClass="Pms\CoreBundle\Entity\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="projects_name", type="string", length=255)
     */
    private $projectName;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ProjectCostItem", mappedBy="project")
     */
    private $projectCostItem;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\PurchaseRequisition", mappedBy="project")
     */
    private $purchaseRequisition;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="project_heads", nullable=true)
     */
    private $projectHead;

    /**
     * @var Area
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\Area")
     * @ORM\JoinColumn(name="projects_area", nullable=true)
     */
    private $projectArea;

    /**
     * @var ProjectCategory
     *
     * @ORM\ManyToOne(targetEntity="Pms\CoreBundle\Entity\ProjectCategory")
     * @ORM\JoinColumn(name="projects_category", nullable=true)
     */
    private $projectCategory;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Pms\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    public function __construct()
    {
        $this->projectCostItem = new ArrayCollection();
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
     * Set projectHead
     *
     * @param User $projectHead
     * @return Project
     */
    public function setProjectHead($projectHead)
    {
        $this->projectHead = $projectHead;

        return $this;
    }

    /**
     * Get projectHead
     *
     * @return User
     */
    public function getProjectHead()
    {
        return $this->projectHead;
    }

    /**
     * Set projectArea
     *
     * @param Area $projectArea
     * @return Project
     */
    public function setProjectArea($projectArea)
    {
        $this->projectArea = $projectArea;

        return $this;
    }

    /**
     * Get projectArea
     *
     * @return Area
     */
    public function getProjectArea()
    {
        return $this->projectArea;
    }

    /**
     * Set projectCategory
     *
     * @param ProjectCategory $projectCategory
     * @return Project
     */
    public function setProjectCategory($projectCategory)
    {
        $this->projectCategory = $projectCategory;

        return $this;
    }

    /**
     * Get projectCategory
     *
     * @return ProjectCategory
     */
    public function getProjectCategory()
    {
        return $this->projectCategory;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Project
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return User
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set createdBy
     *
     * @param User $createdBy
     * @return Project
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
     * @return Project
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
     * @return Project
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
    public function getPurchaseRequisition()
    {
        return $this->purchaseRequisition;
    }
}
