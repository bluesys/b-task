<?php
namespace Btask\BoardBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Entity\ItemType;
use Btask\BoardBundle\Entity\Workgroup;
use Btask\BoardBundle\Entity\Project;
use Btask\BoardBundle\Entity\Collaboration;

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
        $this->executor = $manager->merge($this->getReference('user1'));
        $this->owner = $manager->merge($this->getReference('user2'));

        $this->loadWorkgroups();
        $this->loadProjects();
        $this->loadCollaboration();
        $this->loadItemType();
        $this->loadPostIt();
        $this->loadNotes();
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
     * Load fake post-it
     *
     */
    public function loadPostIt()
    {
        for ($i = 1; $i <= 4; $i++) {
            $postIt = new Item();
            $postIt->setSubject('That is the amazing post-it '.$i);
            $postIt->setType($this->manager->merge($this->getReference($this->types['0'])));
            $postIt->setOwner($this->owner);

            $this->manager->persist($postIt);
        }

        $this->manager->flush();
    }

    /**
     * Load fake notes
     *
     */
    public function loadNotes()
    {
        for ($i = 1; $i <= 4; $i++) {
            $note = new Item();
            $note->setSubject('That is the amazing note '.$i);
            $note->setType($this->manager->merge($this->getReference($this->types['2'])));
            $note->setOwner($this->owner);
            $note->setProject($this->manager->merge($this->getReference('project1')));
            $this->manager->persist($note);
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
            $task->setSubject('That is the amazing task '.$i);
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setOwner($this->owner);
            $task->setExecutor($this->manager->merge($this->getReference('user2')));

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
            $task->setOwner($this->owner);
            $task->setExecutor($this->manager->merge($this->getReference('user2')));
            $task->setProject($this->manager->merge($this->getReference('project1')));

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
            $task->setOwner($this->manager->merge($this->getReference('user2')));
            $task->setExecutor($this->manager->merge($this->getReference('user2')));

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
            $workgroup->setOwner($this->manager->merge($this->getReference('user2')));

            $this->manager->persist($workgroup);
            $this->manager->flush();

            $this->addReference('workgroup'.$i, $workgroup);
        }

        for ($i = 9; $i <= 12; $i++) {
            $workgroup = new Workgroup();
            $workgroup->setName('Workgroup '.$i);
            $workgroup->setOwner($this->manager->merge($this->getReference('user1')));

            $this->manager->persist($workgroup);
            $this->manager->flush();

            $this->addReference('workgroup'.$i, $workgroup);
        }
    }

    /**
     * Load fake projects
     *
     */
    public function loadProjects()
    {
        for ($i = 1; $i <= 10; $i++) {
            $project = new Project();
            $project->setName('Project '.$i);
            $project->setColor('#eee');

            $this->manager->persist($project);
            $this->manager->flush();

            $this->addReference('project'.$i, $project);
        }
    }

    /**
     * Load fake collaboration
     *
     */
    public function loadCollaboration()
    {

        for ($i = 1; $i <= 5; $i++) {
            $collaboration = new Collaboration();
            $collaboration->setParticipant($this->manager->merge($this->getReference('user2')));
            $collaboration->setProject($this->manager->merge($this->getReference('project'.$i)));
            $collaboration->setWorkgroup($this->manager->merge($this->getReference('workgroup'.$i)));
            $collaboration->setOwner(true);
            $this->manager->persist($collaboration);
        }

        for ($i = 6; $i <= 10; $i++) {
            $collaboration = new Collaboration();
            $collaboration->setParticipant($this->manager->merge($this->getReference('user2')));
            $collaboration->setProject($this->manager->merge($this->getReference('project'.$i)));
            $collaboration->setOwner(true);
            $this->manager->persist($collaboration);
        }

        $this->manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
