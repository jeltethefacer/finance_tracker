<?php
// src/User.php

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements JsonSerializable
{
    /** 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    protected $id;
    /** 
     * @ORM\Column(type="string") 
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\OneToMany(targetEntity="Group", mappedBy="user")
     */
    protected $groups;

    public function __construct() {
        $this->groups = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    public function jsonSerialize()
    {
        return array(
            "id" => $this->id,
            "name" => $this->name
        );
    }
}