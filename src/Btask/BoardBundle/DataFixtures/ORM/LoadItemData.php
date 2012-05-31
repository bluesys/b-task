<?php
namespace Btask\BoardBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Entity\ItemType;
use Btask\BoardBundle\Entity\Workgroup;
use Btask\BoardBundle\Entity\WorkgroupCollaboration;
use Btask\BoardBundle\Entity\Project;
use Btask\BoardBundle\Entity\ProjectCollaboration;

/**
 * Load some items in database
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 */
class LoadItemData extends AbstractFixture implements OrderedFixtureInterface
{
    protected $manager;

    protected $types = array('Post-it', 'Task', 'Note');

    protected $owner;

    protected $executor;


    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->owner = $manager->merge($this->getReference('user1'));
        $this->executor = $manager->merge($this->getReference('user2'));

        $this->loadWorkgroups();
        $this->loadWorkgroupCollaboration();
        $this->loadProjects();
        $this->loadProjectCollaboration();
        $this->loadItemType();
        $this->loadOverdueTasks();
        $this->loadPlannedTasks();
        $this->loadDoneTasks();
    }

    /**
     * Load default ItemType
     *
     */
    public function loadItemType()
    {
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
    public function loadOverdueTasks()
    {
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
            $task->setOwner($this->owner);
            $task->setExecutor($this->executor);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }

    /**
     * Load fake tasks to be done for today
     *
     */
    public function loadPlannedTasks()
    {
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
            $task->setOwner($this->owner);
            $task->setExecutor($this->executor);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }


    /**
     * Load fake done tasks
     *
     */
    public function loadDoneTasks()
    {
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
            $task->setOwner($this->owner);
            $task->setOwner($this->owner);
            $task->setExecutor($this->executor);

            $this->manager->persist($task);
        }

        $this->manager->flush();
    }

    /**
     * Load fake workgroups
     *
     */
    public function loadWorkgroups()
    {
        for ($i = 1; $i <= 8; $i++) {
            $workgroup = new Workgroup();
            $workgroup->setName('Workgroup '.$i);

            $this->manager->persist($workgroup);
            $this->manager->flush();

            $this->addReference('workgroup'.$i, $workgroup);
        }
    }

    /**
     * Load attribute fake workgroups to a fake user
     *
     */
    public function loadWorkgroupCollaboration()
    {

        for ($i = 1; $i <= 3; $i++) {
            $workgroupCollaboration = new WorkgroupCollaboration();
            $workgroupCollaboration->setWorkgroup($this->manager->merge($this->getReference('workgroup'.$i)));
            $workgroupCollaboration->setParticipant($this->manager->merge($this->getReference('user2')));
            $workgroupCollaboration->setOwner(true);
            $workgroupCollaboration->setShared(false);
            $this->manager->persist($workgroupCollaboration);
        }

        $this->manager->flush();
    }

    /**
     * Load attribute fake project to a fake workgroup
     *
     */
    public function loadProjects()
    {
        for ($i = 1; $i <= 5; $i++) {
            $project = new Project();
            $project->setName('Project '.$i);
            $project->setColor('#eee');
            $project->addWorkgroup($this->manager->merge($this->getReference('workgroup1')));

            $this->manager->persist($project);
            $this->manager->flush();

            $this->addReference('project'.$i, $project);
        }
    }

    /**
     * Load attribute fake projects to a fake user
     *
     */
    public function loadProjectCollaboration()
    {
        for ($i = 1; $i <= 5; $i++) {
            $projectCollaboration = new ProjectCollaboration();
            $projectCollaboration->setProject($this->manager->merge($this->getReference('project'.$i)));
            $projectCollaboration->setParticipant($this->manager->merge($this->getReference('user2')));
            $projectCollaboration->setOwner(true);

            $this->manager->persist($projectCollaboration);
        }

        $this->manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
