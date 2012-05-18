<?php
namespace Btask\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Btask\DashboardBundle\Entity\ItemType;

/**
 * Load default item type in the database
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 */
class LoadItemTypeData implements FixtureInterface
{
	protected $types = array('Post-it', 'Task', 'Note');

    public function load(ObjectManager $manager)
    {
    	foreach ($this->types as $type) {
	        $itemType = new ItemType();
	        $itemType->setName($type);

	        $manager->persist($itemType);
    	}

        $manager->flush();
    }
}
