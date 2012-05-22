<?php

namespace Btask\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Btask\DashboardBundle\Entity\Item;

/**
 * Btask\UserBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\UserBundle\Entity\UserRepository")
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
     * @ORM\OneToMany(targetEntity="\Btask\DashboardBundle\Entity\Item", mappedBy="owner", cascade={"remove", "persist"})
     */
    protected $items;

    /**
     * @ORM\OneToMany(targetEntity="\Btask\DashboardBundle\Entity\Item", mappedBy="executor", cascade={"remove", "persist"})
     */
    protected $tasks;


    public function __construct()
    {
        parent::__construct();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param Btask\DashboardBundle\Entity\Item $items
     */
    public function addItem(\Btask\DashboardBundle\Entity\Item $items)
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
     * @param Btask\DashboardBundle\Entity\Item $tasks
     */
    public function addTask(\Btask\DashboardBundle\Entity\Item $tasks)
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
}
