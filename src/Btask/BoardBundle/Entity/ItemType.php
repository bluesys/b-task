<?php

namespace Btask\BoardBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btask\BoardBundle\Entity\ItemType
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
     * @ORM\OneToMany(targetEntity="Item", mappedBy="type", cascade={"persist", "remove"})
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
     * @param Btask\BoardBundle\Entity\Item $items
     */
    public function addItem(\Btask\BoardBundle\Entity\Item $items)
    {
        $this->items[] = $items;
    }
}