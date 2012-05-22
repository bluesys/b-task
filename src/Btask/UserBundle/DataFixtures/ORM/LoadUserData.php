<?php
namespace Btask\UserBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Btask\UserBundle\Entity\User;

/**
 * Load a fake user in a database
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
        $user->setEmail('local@localhost.lo');
        $user->setPassword('password');
        $user->setEnabled(true);
        $this->manager->persist($user);
        $this->manager->flush();

        $this->addReference('test_user', $user);
    }

    public function getOrder()
    {
        return 1;
    }
}
