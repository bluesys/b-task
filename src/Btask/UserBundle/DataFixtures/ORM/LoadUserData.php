<?php
namespace Btask\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Btask\UserBundle\Entity\User;

/**
 * Load a fake users in a database
 *
 * @author Geoffroy Perriard <geoffroy.perriard@gmail.com>
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $manager;

    protected $container;


    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {

        $this->manager = $manager;

        $user = $this->container->get('fos_user.user_manager')->createUser();
        $user->setEmail('dumont@bluesystem.ch');
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->addReference('cedric', $user);

        $user = $this->container->get('fos_user.user_manager')->createUser();
        $user->setEmail('bossy@bluesystem.ch');
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->addReference('denis', $user);

        $user = $this->container->get('fos_user.user_manager')->createUser();
        $user->setEmail('buntschu@bluesystem.ch');
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->addReference('nicolas', $user);

        $user = $this->container->get('fos_user.user_manager')->createUser();
        $user->setEmail('joachim@kameleo.ch');
        $user->setPlainPassword('password');
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();
        $this->addReference('joachim', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}
