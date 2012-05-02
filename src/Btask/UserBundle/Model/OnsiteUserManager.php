<?php

namespace Btask\UserBundle\Model;

use FOS\UserBundle\Entity\UserManager;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class OnsiteUserManager extends UserManager
{    
    /**
     * Returns an user instance with ROLE_MEMBER
     *
     * @return UserInterface
     */
    public function createUser()
    {
        $class = $this->getClass();
        $user = new $class;

        // Add ROLE_MEMBER to user who registers
        $user->addRole('ROLE_MEMBER');

        return $user;
    }

    /**
     * Return a user by his email
     *
     * @param string $username
     * @return UserInterface
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
