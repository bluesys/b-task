<?php

namespace Btask\UserBundle\Model;

use FOS\UserBundle\Entity\UserManager;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class OnsiteUserManager extends UserManager
{

    /**
     * Returns an user instance with ROLE_USER
     *
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class;

        // Add ROLE_USER by default
        $user->addRole('ROLE_USER');

        return $user;
    }

    /**
     * Return a user by his email
     *
     * @param string $username
     * @return UserInterface
     * @author https://github.com/FriendsOfSymfony/FOSUserBundle/blob/1.2.0/Resources/doc/logging_by_username_or_email.md
     */
    public function loadUserByUsername($username)
    {
        $user = $this->findUserByEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException(sprintf('No user with name "%s" was found.', $username));
        }

        return $user;
    }
}
