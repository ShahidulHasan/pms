<?php

namespace Pms\UserBundle\Entity;

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
        $this->getRoles();
        $this->addRole($role);

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
}