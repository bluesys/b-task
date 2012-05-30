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
     * @ORM\OneToMany(targetEntity="\Btask\BoardBundle\Entity\WorkgroupCollaboration", mappedBy="participant", cascade={"remove", "persist"})
     */
    protected $workgroupCollaborations;

    /**
     * @ORM\OneToMany(targetEntity="\Btask\BoardBundle\Entity\ProjectCollaboration", mappedBy="participant", cascade={"remove", "persist"})
     */
    protected $projectCollaborations;


    public function __construct()
    {
        parent::__construct();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workgroupCollaborations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projectCollaborations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add workgroup collaboration
     *
     * @param Btask\BoardBundle\Entity\WorkgroupCollaboration $workgroupCollaboration
     */
    public function addWorkgroupCollaboration(\Btask\BoardBundle\Entity\WorkgroupCollaboration $workgroupCollaboration)
    {
        $this->workgroupCollaborations[] = $workgroupCollaboration;
    }

    /**
     * Get workgroup collaboration
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWorkgroupCollaborations()
    {
        return $this->workgroupCollaborations;
    }

    /**
     * Add project collaboration
     *
     * @param Btask\BoardBundle\Entity\ProjectCollaboration $projectCollaborations
     */
    public function addProjectCollaboration(\Btask\BoardBundle\Entity\ProjectCollaboration $projectCollaboration)
    {
        $this->projectCollaborations[] = $projectCollaboration;
    }

    /**
     * Get project collaborations
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProjectCollaborations()
    {
        return $this->projectCollaborations;
    }
}