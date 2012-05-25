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

        for ($i = 1; $i <= 3; $i++) {
            $user = $this->container->get('fos_user.user_manager')->createUser();
            $user->setEmail('local'.$i.'@localhost.lo');
            $user->setPlainPassword('password');
            $user->setEnabled(true);

            $this->manager->persist($user);
            $this->manager->flush();

            $this->addReference('test_user'.$i, $user);
        }
    }

    public function getOrder()
    {
        return 1;
    }
}
