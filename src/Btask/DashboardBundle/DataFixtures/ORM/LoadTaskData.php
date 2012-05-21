<?php
namespace Btask\DashboardBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Btask\DashboardBundle\Entity\Item;
use Btask\DashboardBundle\Entity\ItemType;

/**
 * Load some tasks in database
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 */
class LoadItemData extends AbstractFixture implements FixtureInterface
{
    protected $manager;
    protected $types = array('Post-it', 'Task', 'Note');

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $this->loadItemType();
        $this->loadOverdueTasks();
        $this->loadPlannedTasks();
        $this->loadDoneTasks();
        $this->loadDoneTasks();
    }

    /**
     * Load default ItemType
     *
     */
    public function loadItemType() {

        foreach ($this->types as $type) {
            $itemType = new ItemType();
            $itemType->setName($type);

            $this->manager->persist($itemType);

            $this->addReference($type, $itemType);
        }

        $this->manager->flush();
    }

    /**
     * Load fake overdue tasks
     *
     */
    public function loadOverdueTasks() {

        for ($i = 1; $i <= 4; $i++) {
            $dueDate = new \DateTime('now');
            $plannedDate = $dueDate;
            $plannedDate->modify('-'.$i.' week');
            $dueDate->modify('-'.$i.' day');

            $task = new Item();
            $task->setSubject('Lorem Ipsum is simply dummy text '.$i);
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(true);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }

    /**
     * Load fake tasks to be done for today
     *
     */
    public function loadPlannedTasks() {

        for ($i = 1; $i <= 8; $i++) {
            $dueDate = new \DateTime('now');
            $dueDate->modify('+'.$i.' day');
            $plannedDate = new \DateTime('now');

            $task = new Item();
            $task->setSubject('Lorem Ipsum is simply dummy text '.$i);
            $task->setDue($dueDate);

            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(true);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }


    /**
     * Load fake done tasks
     *
     */
    public function loadDoneTasks() {

        for ($i = 1; $i <= 3; $i++) {
            $dueDate = new \DateTime('now');
            $dueDate->modify('+'.$i.' day');
            $plannedDate = new \DateTime('now');

            $task = new Item();
            $task->setSubject('Lorem Ipsum is simply dummy text '.$i);
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }
}
