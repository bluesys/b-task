<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Btask\BoardBundle\Entity\Workgroup
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\BoardBundle\Entity\WorkgroupRepository")
 */
class Workgroup
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
     * @ORM\OneToMany(targetEntity="UserWorkgroup", mappedBy="workgroup", cascade={"persist", "remove"})
     */
    protected $usersWorkgroups;

    /**
     * @ORM\ManyToMany(targetEntity="Project", mappedBy="workgroups", cascade={"all"})
     * @ORM\JoinTable(name="projects_workgroups", joinColumns={@ORM\JoinColumn(name="workgroup_id", referencedColumnName="id", onDelete="CASCADE")})
     */
    protected $projects;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="_")
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    protected $slug;


    public function __construct()
    {
        $this->usersWorkgroups = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add projects
     *
     * @param Btask\BoardBundle\Entity\Project $projects
     */
    public function addProject(\Btask\BoardBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;
    }

    /**
     * Get projects
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
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
     * Check if the workgroup as owned by user passed in parameter
     *
     * @param \Btask\UserBundle\Entity\User $user
     * @param boolean true|false
     */
    public function hasOwner(\Btask\UserBundle\Entity\User $user)
    {
        foreach ($this->getUsersWorkgroups() as $registredWorkgroup) {
            if( ($registredWorkgroup->getUser() === $user) && ($registredWorkgroup->getOwner()) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the workgroup is shared to the user passed in parameter
     *
     * @param \Btask\UserBundle\Entity\User $user
     * @param boolean true|false
     */
    public function isSharedTo(\Btask\UserBundle\Entity\User $user)
    {
        foreach ($this->getUsersWorkgroups() as $registredWorkgroup) {
            if($registredWorkgroup->getUser() === $user) {
                return true;
            }
        }

        return false;
    }
}