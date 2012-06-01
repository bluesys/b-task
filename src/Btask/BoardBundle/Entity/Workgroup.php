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
     * @ORM\OneToMany(targetEntity="Collaboration", mappedBy="workgroup", cascade={"persist", "remove"})
     */
    protected $collaborations;

    /**
     * @Gedmo\Slug(fields={"name"}, separator="_")
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    protected $slug;

    /**
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="workgroups", cascade={"remove"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    protected $owner;

    /**
     * @var boolean $shared
     *
     * @ORM\Column(name="shared", type="boolean", nullable=false)
     */
    protected $shared;


    public function __construct()
    {
        $this->collaborations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add a collaboration
     *
     * @param Btask\BoardBundle\Entity\Collaboration $collaboration
     */
    public function addCollaboration(\Btask\BoardBundle\Entity\Collaboration $collaboration)
    {
        $this->collaborations[] = $collaboration;
    }

    /**
     * Get collaborations
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCollaborations()
    {
        return $this->collaborations;
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
     * Set owner
     *
     * @param Btask\UserBundle\Entity\User
     */
    public function setOwner(\Btask\UserBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return Btask\UserBundle\Entity\User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set shared
     *
     * @param boolean $shared
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
     * Check if the workgroup is shared to the user passed in parameter
     *
     * @param \Btask\UserBundle\Entity\User $user
     * @param boolean true|false
     */
    public function hasOwner(\Btask\UserBundle\Entity\User $user)
    {
        if($user === $this->owner) {
            return true;
        }

        return false;
    }
}