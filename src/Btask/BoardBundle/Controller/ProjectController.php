<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Project;

class ProjectController extends Controller
{
	/*
	public function showProjectsByWorkgroupAction($workgroup_slug)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			$em = $this->getDoctrine()->getEntityManager();
			$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findBy(array('slug' => $workgroup_slug, 'user' => $user->getId()));
			if (!$workgroup) {
				throw new NotFoundHttpException();
			}

			$projects = $em->getRepository('BtaskBoardBundle:Project')->findBy('workgroup' => $workgroup->getId());
			if (!$projects) {
				throw new NotFoundHttpException();
			}

			return $this->render('BtaskBoardBundle:Overview:project.html.twig', array(
				'projects' => $projects,
			));
		}
	}
	*/

	/**
     * Delete a project
     *
     */
	public function deleteProjectAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get the project
			$em = $this->getDoctrine()->getEntityManager();
			$project = $em->getRepository('BtaskBoardBundle:Project')->find($id);

			if ($project && $project->hasOwner($user)) {
				$em->remove($workgroup);
				$em->flush();

				// TODO: Return a notification
				return new Response(null, 200);
			}
			else {
				throw new NotFoundHttpException();
			}
		}
	}
}
