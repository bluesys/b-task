<?php

namespace Btask\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btask\DashboardBundle\Entity\Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\DashboardBundle\Entity\ItemRepository")
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
     * @var integer $version
     *
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * @var boolean $current
     *
     * @ORM\Column(name="current", type="boolean")
     */
    private $current;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var text $subject
     *
     * @ORM\Column(name="subject", type="text")
     */
    private $subject;

    /**
     * @var text $detail
     *
     * @ORM\Column(name="detail", type="text")
     */
    private $detail;

    /**
     * @var boolean $priority
     *
     * @ORM\Column(name="priority", type="boolean")
     */
    private $priority;

    /**
     * @var boolean $status
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var datetime $plannedAt
     *
     * @ORM\Column(name="planned_at", type="datetime")
     */
    private $plannedAt;

    /**
     * @var datetime $dueAt
     *
     * @ORM\Column(name="due_at", type="datetime")
     */
    private $dueAt;

    /**
     * @var string $validationToken
     *
     * @ORM\Column(name="validation_token", type="string", length=255)
     */
    private $validationToken;

    /**
     * @ORM\ManyToOne(targetEntity="itemType", inversedBy="items", cascade={"remove"})
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
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
     * Set version
     *
     * @param integer $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * Get version
     *
     * @return integer 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set current
     *
     * @param boolean $current
     */
    public function setCurrent($current)
    {
        $this->current = $current;
    }

    /**
     * Get current
     *
     * @return boolean 
     */
    public function getCurrent()
    {
        return $this->current;
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
     *
     * @param string $validationToken
     */
    public function setValidationToken($validationToken)
    {
        $this->validationToken = $validationToken;
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