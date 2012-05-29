<?php

namespace Btask\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Btask\BoardBundle\Entity\Item;

/**
 * Btask\UserBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="\Btask\BoardBundle\Entity\Item", mappedBy="owner", cascade={"persist"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="\Btask\BoardBundle\Entity\Item", mappedBy="executor", cascade={"persist"})
     */
    protected $tasks;

    /**
     * @ORM\OneToMany(targetEntity="\Btask\BoardBundle\Entity\UserWorkgroup", mappedBy="user", cascade={"remove", "persist"})
     */
    protected $usersWorkgroups;


    public function __construct()
    {
        parent::__construct();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usersWorkgroups = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set email and username as email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->setUsername($email);
        $this->email = $email;
    }

    /**
     * Add items
     *
     * @param Btask\BoardBundle\Entity\Item $items
     */
    public function addItem(\Btask\BoardBundle\Entity\Item $items)
    {
        $this->items[] = $items;
    }

    /**
     * Get items
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add tasks
     *
     * @param Btask\BoardBundle\Entity\Item $tasks
     */
    public function addTask(\Btask\BoardBundle\Entity\Item $tasks)
    {
        $this->tasks[] = $tasks;
    }

    /**
     * Get tasks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Add usersWorkgroups
     *
     * @param Btask\BoardBundle\Entity\UserWorkgroup $usersWorkgroups
     */
    public function addUserWorkgroup(\Btask\BoardBundle\Entity\UserWorkgroup $usersWorkgroups)
    {
        $this->usersWorkgroups[] = $usersWorkgroups;
    }

    /**
     * Get usersWorkgroups
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getUsersWorkgroups()
    {
        return $this->usersWorkgroups;
    }
}