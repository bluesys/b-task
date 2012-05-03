<?php

namespace Btask\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * Btask\UserBundle\Entity\User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Btask\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     * @ORM\Column(name="limited_dashboard_token", type="integer")
     */
    protected $limitedDashboardToken;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the dashboard limited token
     *
     * @return string
     */
    public function getLimitedDashboardToken()
    {
        return $this->limitedDashboardToken;
    }

    /**
     * Sets the dashboard limited token
     *
     * @param string $limitedDashboardToken
     * @return User
     */
    public function setLimitedDashboardToken($limitedDashboardToken)
    {
        $this->limitedDashboardToken = $limitedDashboardToken;
        return $this;
    }

    /**
     * Set email and username as email
     *
     * @param string $email
     */
    public function setEmail($email) {
        $this->setUsername($email);
        $this->email = $email;
    }
}