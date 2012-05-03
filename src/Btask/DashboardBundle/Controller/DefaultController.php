<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BtaskDashboardBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * Display the limited dashboard of an invited user provided by the token from the URL
     *
     * @param string $token;
     */
    public function showLimitedDashboardAction($token)
    {
    	$user = $this->container->get('fos_user.user_manager')->findUserBy(array('limitedDashboardToken' => $token));
        
        if (null == $user) {
            throw new NotFoundHttpException(sprintf('The user with this limited dashboard token "%s" does not exist', $token));
        }

		$this->authenticateUser($user);
    	return $this->redirect($this->generateUrl('BtaskDashboardBundle_homepage', array('name' => $user->getEmail())));
    }

    /**
     * Authentificate a user by his instance
     * TODO: Use the way from FOS\UserBundle\Controller\RegistrationController
     *
     * @param UserInterface $user;
     */
    protected function authenticateUser(UserInterface $user)
    {
        try {
            $this->container->get('fos_user.user_checker')->checkPostAuth($user);
        } catch (AccountStatusException $e) {
            // Don't authenticate locked, disabled or expired users
            return;
        }

        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());

        $this->container->get('security.context')->setToken($token);
    }
}
