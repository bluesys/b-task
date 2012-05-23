<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Btask\UserBundle\Entity\User;

/**
 * Btask\BoardBundle\Entity\Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\BoardBundle\Entity\ItemRepository")
 * @Gedmo\Loggable
 */
class Item
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $created;

    /**
     * @var datetime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updated;

    /**
     * @var text $subject
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="subject", type="text")
     */
    protected $subject;

    /**
     * @var text $detail
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="detail", type="text", nullable=true)
     */
    protected $detail;

    /**
     * @var boolean $priority
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="priority", type="boolean", nullable=true)
     */
    protected $priority;

    /**
     * @var boolean $status
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    protected $status;

    /**
     * @var datetime $planned
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="planned_at", type="date", nullable=true)
     */
    protected $planned;

    /**
     * @var datetime $due
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="due_at", type="date", nullable=true)
     */
    protected $due;

    /**
     * @var string $validationToken
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="validation_token", type="string", length=255, nullable=true)
     */
    protected $validationToken;

    /**
     * @Gedmo\Versioned
     *
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items", cascade={"remove"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    protected $type;

    /**
     * @Gedmo\Versioned
     *
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="items", cascade={"remove"})
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    protected $project;

    /**
     * @Gedmo\Versioned
     *
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="items", cascade={"remove"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", nullable=false)
     */
    protected $owner;

    /**
     * @Gedmo\Versioned
     *
     * @ORM\ManyToOne(targetEntity="\Btask\UserBundle\Entity\User", inversedBy="tasks", cascade={"remove"})
     * @ORM\JoinColumn(name="executor_id", referencedColumnName="id")
     */
    protected $executor;


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
     * Set subject
     *
     * @param text $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Get subject
     *
     * @return text 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set detail
     *
     * @param text $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * Get detail
     *
     * @return text 
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * Set priority
     *
     * @param boolean $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * Get priority
     *
     * @return boolean 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set validationToken
     * Generate the token by mixing crypted user and item information
     * @param User $user
     */
    public function setValidationToken(User $user)
    {
        $userId = hash('sha256', $user->getId());
        $itemId = hash('sha256', $this->getId());
        $itemCreationDate = hash('sha256', $this->getCreatedAt());
        $salt = hash('sha256', uniqid(mt_rand(), true), true);

        $this->validationToken = md5($userEmail.$itemId.$itemCreationDate.$salt);
    }

    /**
     * Get validationToken
     *
     * @return string 
     */
    public function getValidationToken()
    {
        return $this->validationToken;
    }

    /**
     * Set type
     *
     * @param Btask\BoardBundle\Entity\itemType $type
     */
    public function setType(\Btask\BoardBundle\Entity\itemType $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return Btask\BoardBundle\Entity\itemType 
     */
    public function getType()
    {
        return $this->type;
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
     * Set planned
     *
     * @param datetime $planned
     */
    public function setPlanned($planned)
    {
        $this->planned = $planned;
    }

    /**
     * Get planned
     *
     * @return datetime 
     */
    public function getPlanned()
    {
        return $this->planned;
    }

    /**
     * Set due
     *
     * @param datetime $due
     */
    public function setDue($due)
    {
        $this->due = $due;
    }

    /**
     * Get due
     *
     * @return datetime 
     */
    public function getDue()
    {
        return $this->due;
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
     * Set owner
     *
     * @param Btask\UserBundle\Entity\User $owner
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
     * Set executor
     *
     * @param Btask\UserBundle\Entity\User $executor
     */
    public function setExecutor(\Btask\UserBundle\Entity\User $executor)
    {
        $this->executor = $executor;
    }

    /**
     * Get executor
     *
     * @return Btask\UserBundle\Entity\User
     */
    public function getExecutor()
    {
        return $this->executor;
    }

}
