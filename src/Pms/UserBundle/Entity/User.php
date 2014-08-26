<?php

namespace Pms\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="Pms\UserBundle\Entity\UserRepository")
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
}