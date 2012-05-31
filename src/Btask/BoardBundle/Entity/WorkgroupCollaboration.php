<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\WorkgroupCollaboration
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WorkgroupCollaboration
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
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="workgroupCollaborations", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $participant;

	/**
     * @ORM\ManyToOne(targetEntity="Workgroup", inversedBy="participations", cascade={"persist"})
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
     * @var boolean $shared
     *
     * @ORM\Column(name="shared", type="boolean", nullable=false)
     */
    protected $shared;


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
     * Set shared
     *
     * @param boolean $owner
     */
    public function setShared($shared)
    {
        $this->shared = $shared;
    }

    /**
     * Get shared
     *
     * @return boolean
     */
    public function getShared()
    {
        return $this->shared;
    }

    /**
     * Set participant
     *
     * @param Btask\UserBundle\Entity\User $participant
     */
    public function setParticipant(\Btask\UserBundle\Entity\User $participant)
    {
        $this->participant = $participant;
    }

    /**
     * Get participant
     *
     * @return Btask\UserBundle\Entity\User
     */
    public function getParticipant()
    {
        return $this->participant;
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