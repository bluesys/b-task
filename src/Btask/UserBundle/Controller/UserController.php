<?php

namespace Btask\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\UserBundle\Entity\User;

class UserController extends Controller
{
	/**
	 * Create a user
	 *
	 */
    public function createUserAction($email)
    {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		if(!is_valid_email($email)) {
			throw new NotFoundHttpException();
		}

		// Check if there already a user with this email
		$em = $this->getDoctrine()->getEntityManager();
		$user = $em->getRepository('BtaskBoardBundle:User')->findOneByEmail($email);

		// TODO: Throw a correct exception
		if($user) {
			throw new NotFoundHttpException();
		}

		// Assign an empty password to the new user
		$user->setEmail($email);
		$user->setPlainPassword('');
		$user->setEnabled(true);

        return $this->render('BtaskUserBundle:User:user.html.twig', array(
        	'user' => $user;
        ));

		$em->persist($user);
		$em->flush();
    }

}
