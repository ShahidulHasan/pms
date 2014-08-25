<?php

namespace Pms\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Pms\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $role;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ProjectCost", mappedBy="createdBy")
     */
    private $projectCostCreated;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Pms\CoreBundle\Entity\ProjectCost", mappedBy="approvedBy")
     */
    private $projectCostApproved;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function toArray($collection)
    {
        $this->setRoles($collection->toArray());
    }

    public function setRole($role)
    {
        $this->setRoles(array($role));

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        $role = $this->getRoles();

        return $role[0];
    }

    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProjectCostCreated()
    {
        return $this->projectCostCreated;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProjectCostApproved()
    {
        return $this->projectCostApproved;
    }
}