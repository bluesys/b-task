<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\UserWorkgroup
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class UserWorkgroup
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
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="usersWorkgroups", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

	/**
     * @ORM\ManyToOne(targetEntity="Workgroup", inversedBy="usersWorkgroups", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="workgroup_id", referencedColumnName="id", nullable=false)
     */
    protected $workgroup;

	/**
     * @var boolean $owner
     *
     * @ORM\Column(name="owner", type="boolean", nullable=false)
     */
    protected $owner;


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
     * Set owner
     *
     * @param boolean $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return boolean 
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set user
     *
     * @param Btask\UserBundle\Entity\User $user
     */
    public function setUser(\Btask\UserBundle\Entity\User $user)
    {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return Btask\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set workgroup
     *
     * @param Btask\BoardBundle\Entity\Workgroup $workgroup
     */
    public function setWorkgroup(\Btask\BoardBundle\Entity\Workgroup $workgroup)
    {
        $this->workgroup = $workgroup;
    }

    /**
     * Get workgroup
     *
     * @return Btask\BoardBundle\Entity\Workgroup 
     */
    public function getWorkgroup()
    {
        return $this->workgroup;
    }
}