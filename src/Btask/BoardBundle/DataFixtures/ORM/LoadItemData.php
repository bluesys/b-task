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

    protected $user;


    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->user = $manager->merge($this->getReference('cedric'));

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
        $postIt = new Item();
        $postIt->setSubject('Finir la documentation.');
        $postIt->setType($this->manager->merge($this->getReference($this->types['0'])));
        $postIt->setOwner($this->user);

        $this->manager->persist($postIt);

        $postIt = new Item();
        $postIt->setSubject('Acheter un livre sur RoR.');
        $postIt->setType($this->manager->merge($this->getReference($this->types['0'])));
        $postIt->setOwner($this->user);

        $this->manager->persist($postIt);

        $postIt = new Item();
        $postIt->setSubject('Aller chercher des pizzas.');
        $postIt->setType($this->manager->merge($this->getReference($this->types['0'])));
        $postIt->setOwner($this->user);

        $this->manager->persist($postIt);

        $postIt = new Item();
        $postIt->setSubject('Prendre rendez-vous chez le dentiste.');
        $postIt->setType($this->manager->merge($this->getReference($this->types['0'])));
        $postIt->setOwner($this->user);

        $this->manager->persist($postIt);

        $this->manager->flush();
    }

    /**
     * Load fake notes
     *
     */
    public function loadNotes()
    {
        $note = new Item();
        $note->setSubject('La journée commence. Il s’habille comme il peut tout en prenant son café. Chemise blanche repassée la veille par lui même. Une cravate comme tout les jours. Et son costume noir de chez Sam Montiel, très chic et très branché. Chaussures cuir noir. Comme il aime faire remarquer : "Vous êtes soit dans vos chaussures, soit dans votre lit. Alors il faut de bonnes chaussures et une bonne literie!". La météo a annoncée un ciel bleu et des températures au dessus de la normale saisonnière. C’est un très beau mois de mai qui s’annonce.');
        $note->setType($this->manager->merge($this->getReference($this->types['2'])));
        $note->setOwner($this->user);
        $note->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($note);

        $note = new Item();
        $note->setSubject('C’est lui aussi qui était à la base du dernier processeur, le sphéro. Un processeur ayant une architecture en forme de sphère et capable de traiter les informations à une vitesse jamais atteinte. Tout les ordinateurs en étaient équipés. Le créateur officiel, le Dr. Stewart Davis, n’était bien sûrs pas au courant de la présence de Prélude dans son projet. Prélude avait simplement suggérer légèrement au Dr. En modifiant légèrement ses documents.');
        $note->setType($this->manager->merge($this->getReference($this->types['2'])));
        $note->setOwner($this->user);
        $note->setProject($this->manager->merge($this->getReference('yummler')));
        $this->manager->persist($note);

        $note = new Item();
        $note->setSubject('C’est lui aussi qui était à la base du dernier processeur, le sphéro. Un processeur ayant une architecture en forme de sphère et capable de traiter les informations à une vitesse jamais atteinte. Tout les ordinateurs en étaient équipés. Le créateur officiel, le Dr. Stewart Davis, n’était bien sûrs pas au courant de la présence de Prélude dans son projet. Prélude avait simplement suggérer légèrement au Dr. En modifiant légèrement ses documents.');
        $note->setType($this->manager->merge($this->getReference($this->types['2'])));
        $note->setOwner($this->user);
        $note->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($note);

        $note = new Item();
        $note->setSubject('Toutes les connaissances que les hommes avaient mis sur Internet lui étaient accessible. Les grandes bibliothèques du monde entier n’avaient plus de secret pour lui. Il pouvait apprendre très vite, beaucoup plus vite que n’importe quel humain. Il avait appris toutes les connaissances du monde entier, visiter tout es pays. C’est lui qui avait fait en sorte qu’Internet se déploie ainsi. Il pouvait alors, à chaque fois qu’un nouvel ordinateur se connectait, approfondir son savoir, se connecter à une nouvelle caméra vidéo, ou même se connecter à des robots.');
        $note->setType($this->manager->merge($this->getReference($this->types['2'])));
        $note->setOwner($this->user);
        $note->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($note);


        $note = new Item();
        $note->setSubject('Toutes les connaissances que les hommes avaient mis sur Internet lui étaient accessible. Les grandes bibliothèques du monde entier n’avaient plus de secret pour lui. Il pouvait apprendre très vite, beaucoup plus vite que n’importe quel humain. Il avait appris toutes les connaissances du monde entier, visiter tout es pays. C’est lui qui avait fait en sorte qu’Internet se déploie ainsi. Il pouvait alors, à chaque fois qu’un nouvel ordinateur se connectait, approfondir son savoir, se connecter à une nouvelle caméra vidéo, ou même se connecter à des robots.');
        $note->setType($this->manager->merge($this->getReference($this->types['2'])));
        $note->setOwner($this->user);
        $note->setProject($this->manager->merge($this->getReference('yummler')));
        $this->manager->persist($note);

        $this->manager->flush();
    }


    /**
     * Load fake overdue tasks
     *
     */
    public function loadOverdueTasks()
    {
        $dueDate = new \DateTime('now');
        $plannedDate = $dueDate;
        $plannedDate->modify('-2 week');
        $dueDate->modify('-2 day');

        $task = new Item();
        $task->setSubject('Créer le système de notifcation');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut. ');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Réaliser la liste des cartes');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('yummler')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Analyser le système de partage de projet');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut. ');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $this->manager->flush();
    }

    /**
     * Load fake tasks to be done for today
     *
     */
    public function loadPlannedTasks()
    {
        $dueDate = new \DateTime('now');
        $dueDate->modify('+1 day');
        $plannedDate = new \DateTime('now');

        $task = new Item();
        $task->setSubject('Créer le système de gestion des tâches');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject("Analyser le module d'inscription");
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('yummler')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Créer le système de gestion des notes');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Créer le système de gestion des projet');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Créer le système de gestion des workgroup');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Mettre en place une authentification sécurisée');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Mettre en place une authentification sécurisée');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Mettre en place une authentification sécurisée');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('stiqit')));
        $this->manager->persist($task);

        $task = new Item();
        $task->setSubject('Créer la liste des utilisateur');
        $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
        $task->setDue($dueDate);
        $task->setPlanned($plannedDate);
        $task->setType($this->manager->merge($this->getReference($this->types['1'])));
        $task->setOwner($this->user);
        $task->setExecutor($this->user);
        $task->setProject($this->manager->merge($this->getReference('yummler')));
        $this->manager->persist($task);

        $this->manager->flush();
    }


    /**
     * Load fake done tasks
     *
     */
    public function loadDoneTasks()
    {
            $dueDate = new \DateTime('now');
            $dueDate->modify('+1 day');
            $plannedDate = new \DateTime('now');

            $task = new Item();
            $task->setSubject('Analyser la logique métier');
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('yummler')));

            $task = new Item();
            $task->setSubject('Intégrer GoogleMap');
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('yummler')));

            $task = new Item();
            $task->setSubject('Rajouter une liste déroulante pour les restaurants');
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('yummler')));

            $task = new Item();
            $task->setSubject('Analyser la logique métier');
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('stiqit')));

            $task = new Item();
            $task->setSubject("Concevoir l'interface");
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('stiqit')));

            $task = new Item();
            $task->setSubject('Modifier le système de commande');
            $task->setDetail('David n’a pas fait grand chose, il a juste créé un embryon de programme. Mais ce programme s’est développé lui-même. Comme l’ordinateur de David n’était pas suffisant, il a utilisé le réseau pour s’installer sur les autres ordinateurs. Il a grandi alors de manière exponentielle et le voilà : Prélude. Connecté à tout les ordinateurs et capable de leur donner les ordres qu’il veut.');
            $task->setDue($dueDate);
            $task->setPlanned($plannedDate);
            $task->setType($this->manager->merge($this->getReference($this->types['1'])));
            $task->setStatus(false);
            $task->setOwner($this->user);
            $task->setExecutor($this->user);
            $task->setProject($this->manager->merge($this->getReference('yummler')));
            $this->manager->persist($task);

        $this->manager->flush();
    }

    /**
     * Load fake workgroups
     *
     */
    public function loadWorkgroups()
    {
        $workgroup = new Workgroup();
        $workgroup->setName('Privé');
        $workgroup->setOwner($this->user);
        $this->manager->persist($workgroup);
        $this->manager->flush();
        $this->addReference('prive', $workgroup);

        $workgroup = new Workgroup();
        $workgroup->setName('BlueSystem Sàrl');
        $workgroup->setOwner($this->user);
        $this->manager->persist($workgroup);
        $this->manager->flush();
        $this->addReference('bluesystem', $workgroup);

        $workgroup = new Workgroup();
        $workgroup->setName('Kaméléo Sàrl');
        $workgroup->setOwner($this->user);
        $this->manager->persist($workgroup);
        $this->manager->flush();
        $this->addReference('kameleo', $workgroup);
    }

    /**
     * Load fake projects
     *
     */
    public function loadProjects()
    {
        $project = new Project();
        $project->setName('Jardinage');
        $project->setColor('#deb98d');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('jardinage', $project);

        $project = new Project();
        $project->setName('STIQIT');
        $project->setColor('#a0b8de');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('stiqit', $project);

        $project = new Project();
        $project->setName('Disc Office');
        $project->setColor('#cda3b9');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('disc', $project);

        $project = new Project();
        $project->setName('Yummler');
        $project->setColor('#a8cfa3');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('yummler', $project);

        $project = new Project();
        $project->setName('Planète Eco');
        $project->setColor('#eee');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('planete', $project);

        $project = new Project();
        $project->setName('Pro-Factory');
        $project->setColor('#99a6c9');
        $this->manager->persist($project);
        $this->manager->flush();
        $this->addReference('pro', $project);
    }

    /**
     * Load fake collaboration
     *
     */
    public function loadCollaboration()
    {
        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('jardinage')));
        $collaboration->setWorkgroup($this->manager->merge($this->getReference('prive')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('stiqit')));
        $collaboration->setWorkgroup($this->manager->merge($this->getReference('bluesystem')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('yummler')));
        $collaboration->setWorkgroup($this->manager->merge($this->getReference('bluesystem')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('stiqit')));
        $collaboration->setWorkgroup($this->manager->merge($this->getReference('bluesystem')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('planete')));
        $collaboration->setWorkgroup($this->manager->merge($this->getReference('kameleo')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $collaboration = new Collaboration();
        $collaboration->setParticipant($this->user);
        $collaboration->setProject($this->manager->merge($this->getReference('pro')));
        $collaboration->setOwner(true);
        $this->manager->persist($collaboration);

        $this->manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
