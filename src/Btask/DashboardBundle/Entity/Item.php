<?php

namespace Btask\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Btask\UserBundle\Entity\User;

/**
 * Btask\DashboardBundle\Entity\Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\DashboardBundle\Entity\ItemRepository")
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
    private $id;

    /**
     * @var datetime $createdAt
     * @Gedmo\Versioned
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var text $subject
     * @Gedmo\Versioned
     * @ORM\Column(name="subject", type="text")
     */
    private $subject;

    /**
     * @var text $detail
     * @Gedmo\Versioned
     * @ORM\Column(name="detail", type="text", nullable=true)
     */
    private $detail;

    /**
     * @var boolean $priority
     * @Gedmo\Versioned
     * @ORM\Column(name="priority", type="boolean", nullable=true)
     */
    private $priority;

    /**
     * @var boolean $status
     * @Gedmo\Versioned
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var datetime $plannedAt
     * @Gedmo\Versioned
     * @ORM\Column(name="planned_at", type="datetime", nullable=true)
     */
    private $plannedAt;

    /**
     * @var datetime $dueAt
     * @Gedmo\Versioned
     * @ORM\Column(name="due_at", type="datetime", nullable=true)
     */
    private $dueAt;

    /**
     * @var string $validationToken
     * @Gedmo\Versioned
     * @ORM\Column(name="validation_token", type="string", length=255, nullable=true)
     */
    private $validationToken;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="ItemType", inversedBy="items", cascade={"remove"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id", nullable=false)
     */
    protected $type;

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
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set subject
     *
     * @gedmo:Versioned
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
     * Set plannedAt
     *
     * @param datetime $plannedAt
     */
    public function setPlannedAt($plannedAt)
    {
        $this->plannedAt = $plannedAt;
    }

    /**
     * Get plannedAt
     *
     * @return datetime 
     */
    public function getPlannedAt()
    {
        return $this->plannedAt;
    }

    /**
     * Set dueAt
     *
     * @param datetime $dueAt
     */
    public function setDueAt($dueAt)
    {
        $this->dueAt = $dueAt;
    }

    /**
     * Get dueAt
     *
     * @return datetime 
     */
    public function getDueAt()
    {
        return $this->dueAt;
    }

    /**
     * Set validationToken
     * Generate the token by mixing crypted user and item information
     * @param User $user
     */
    public function setValidationToken(User $user)
    {
        $userId = hash('sha256', $user->getId()));
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
     * @param Btask\DashboardBundle\Entity\itemType $type
     */
    public function setType(\Btask\DashboardBundle\Entity\itemType $type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return Btask\DashboardBundle\Entity\itemType 
     */
    public function getType()
    {
        return $this->type;
    }
}