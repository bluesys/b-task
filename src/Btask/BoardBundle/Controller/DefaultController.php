<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Project;

class DefaultController extends Controller
{
    public function showBoardAction()
    {
        return $this->render('BtaskBoardBundle::layout.html.twig');
    }

    public function showTodayAction()
    {
        return $this->render('BtaskBoardBundle:Default:today.html.twig');
    }

	/**
	 * Display the project view
	 * 
	 */
    public function showProjectAction($project_slug)
    {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the project
		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->findOneBySlug($project_slug);
		
		if(!project) {
			throw new NotFoundHttpException();
		}
		
		if(!project->isSharedTo($user)) {
            throw new AccessDeniedHttpException();
		}
		
        return $this->render('BtaskBoardBundle:Default:project.html.twig', array(
			'project' => $project,
        ));
    }
}
