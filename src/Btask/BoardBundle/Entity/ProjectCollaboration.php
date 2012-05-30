<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\ProjectCollaboration
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ProjectCollaboration
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
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="projectCollaborations", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $participant;

	/**
     * @ORM\ManyToOne(targetEntity="Workgroup", inversedBy="participations", cascade={"persist"})
     * @ORM\JoinColumn(name="workgroup_id", referencedColumnName="id", nullable=false)
     */
    protected $project;

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
}