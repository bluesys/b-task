<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\Project
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\BoardBundle\Entity\ProjectRepository")
 */
class Project
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
     * @var datetime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime")
     */
    protected $updated;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string $color
     *
     * @ORM\Column(name="color", type="string", length=255)
     */
    protected $color;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="project", cascade={"persist"})
     */
    protected $tasks;

    /**
     * @ORM\ManyToMany(targetEntity="Workgroup", inversedBy="projects", cascade={"all"})
     * @ORM\JoinTable(name="projects_workgroups", joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    protected $workgroups;

    /**
     * @ORM\OneToMany(targetEntity="ProjectCollaboration", mappedBy="project", cascade={"persist", "remove"})
     */
    protected $participations;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="_")
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    protected $slug;


    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
        $this->workgroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set created
     *
     * @param datetime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * Get updated
     *
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set color
     *
     * @param string $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slig = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add tasks
     *
     * @param Btask\BoardBundle\Entity\Item $tasks
     */
    public function addItem(\Btask\BoardBundle\Entity\Item $tasks)
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
     * Add workgroups
     *
     * @param Btask\BoardBundle\Entity\Workgroup $workgroups
     */
    public function addWorkgroup(\Btask\BoardBundle\Entity\Workgroup $workgroups)
    {
        $this->workgroups[] = $workgroups;
    }

    /**
     * Get workgroups
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getWorkgroups()
    {
        return $this->workgroups;
    }

    /**
     * Add participations
     *
     * @param Btask\BoardBundle\Entity\ProjectCollaboration $participation
     */
    public function addParticipation(\Btask\BoardBundle\Entity\ProjectCollaboration $participation)
    {
        $this->participations[] = $participation;
    }

    /**
     * Get participations
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * Check if the project as owned by user
     *
     * @param \Btask\UserBundle\Entity\User $user
     * @param boolean true|false
     */
    public function hasOwner(\Btask\UserBundle\Entity\User $user)
    {
        foreach ($this->getParticipations() as $registredParticipation) {
            if( ($registredParticipation->getParticipant() === $user) && ($registredParticipation->getOwner()) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the project is shared to the user
     *
     * @param \Btask\UserBundle\Entity\User $user
     * @param boolean true|false
     */
    public function isSharedTo(\Btask\UserBundle\Entity\User $user)
    {
        foreach ($this->getParticipations() as $registredParticipation) {
            if($registredParticipation->getParticipant() === $user) {
                return true;
            }
        }

        return false;
    }
}
