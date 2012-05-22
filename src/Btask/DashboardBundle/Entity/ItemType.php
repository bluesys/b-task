<?php

namespace Btask\DashboardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btask\DashboardBundle\Entity\ItemType
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemType
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="type", cascade={"remove", "persist"})
     */
    protected $items;

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
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get items
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add items
     *
     * @param Btask\DashboardBundle\Entity\Item $items
     */
    public function addItem(\Btask\DashboardBundle\Entity\Item $items)
    {
        $this->items[] = $items;
    }
}