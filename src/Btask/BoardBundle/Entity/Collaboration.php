<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\Collaboration
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Collaboration
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
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="collaborations", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $participant;

	/**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="collaborations", cascade={"persist"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    protected $project;

    /**
     * @ORM\ManyToOne(targetEntity="Workgroup", inversedBy="collaborations", cascade={"persist"})
     * @ORM\JoinColumn(name="workgroup_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
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
     * Set project
     *
     * @param Btask\BoardBundle\Entity\Project $project
     */
    public function setProject(\Btask\BoardBundle\Entity\Project $project)
    {
        $this->project = $project;
    }

    /**
     * Get project
     *
     * @return Btask\BoardBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
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