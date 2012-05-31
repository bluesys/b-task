<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Project;
use Btask\BoardBundle\Form\Type\ProjectType;

class ProjectController extends Controller
{
	/**
	 * Display a project
	 *
	 */
	public function showProjectAction($id) {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the project
		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->findBy(array('id' => $id, 'participant' => $user->getId()));

		if (!$project) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		return $this->render('BtaskBoardBundle:Overview:project.html.twig', array(
			'project' => $project[0],
		));
	}


	/**
	 * Display projects which are in a specific workgroup
	 *
	 */
	public function showProjectsByWorkgroupAction($workgroup_slug)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the workgroup
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findOneBy(array('slug' => $workgroup_slug));

		if (!$workgroup) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Get all projects which are in the workgroup
		$projects = $em->getRepository('BtaskBoardBundle:Project')->findBy(array('workgroup' => $workgroup->getId(), 'participant' => $user->getId()));
		if (!$projects) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Return a JSON feed of workgroup templates
		$projects_template[] = array();
		foreach ($projects as $workgroup) {
	    	$projects_template[] = $this->render('BtaskBoardBundle:Overview:project.html.twig', array('project' => $project))->getContent();
		}

		$response = new Response(json_encode($projects_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}


	/**
	 * Display all users which participe to the project
	 *
	 */
	public function showParticipantsAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the users which participe to the project
		$em = $this->getDoctrine()->getEntityManager();
		$users = $em->getRepository('BtaskUserBundle:User')->findBy(array('projectCollaboration' => $id));

		if (!$users) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Return a JSON feed of workgroup templates
		$users_template = array();
		foreach ($users as $user) {
		    $users_template[] = $this->render('BtaskBoardBundle:Overview:user.html.twig', array('user' => $user))->getContent();
		}

		$response = new Response(json_encode($users_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

   /**
     * Display a form to create a workgroup
     *
     */
	public function createProjectAction()
	{
		$request = $this->container->get('request');
		/*if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}*/

		$user = $this->get('security.context')->getToken()->getUser();

		// Generate the form
		// TODO: Move this logic below in a form handler
		$project = new Project;
	    $form = $this->createForm(new ProjectType(), $project);

	    if($request->getMethod() == 'POST') {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
				$em = $this->getDoctrine()->getEntityManager();

				// TODO: Move this logic in a listener
				// Assign the project in the shared workgroup if his wasn't assigned
				if(!$project->getWorkgroups()) {
					$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findBy(array('participant' => $user->getId(), 'shared' => true));
					$project->setWorkgroups($workgroup);
				}

	        	$em->persist($project);
	            $em->flush();

				// TODO: Move this logic in a listener
	            // Assign the project to the current user
				$projectCollaboration = new ProjectCollaboration;
				$projectCollaboration->setParticipant($user);
				$projectCollaboration->setProject($workgroup);
				$projectCollaboration->setOwner(true);

	            $em->persist($workgroupCollaboration);
	            $em->flush();

	            // TODO: Return a notification
				$response = new Response(json_encode($users_template), 200);
				$response->headers->set('Content-Type', 'application/json');

				return $response;
	        }
	    }

		return $this->render('BtaskBoardBundle:Overview:form_create_workgroup.html.twig', array(
			'form' => $form->createView(),
		));
	}


	/**
     * Delete a project
     *
     */
	public function deleteProjectAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the project
		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->find($id);

		if(!$project) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Check if the current user can delete the project
		if(!$project->hasOwner($user)) {
            throw new AccessDeniedHttpException();
		}

		$em->remove($workgroup);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
