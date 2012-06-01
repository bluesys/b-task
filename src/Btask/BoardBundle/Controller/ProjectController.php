<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Project;
use Btask\BoardBundle\Form\Type\ProjectType;
use Btask\BoardBundle\Entity\Collaboration;
use Btask\BoardBundle\Form\Type\CollaborationType;
use Btask\BoardBundle\Form\Handler\CollaborationHandler;

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
		$project = $em->getRepository('BtaskBoardBundle:Project')->find($id);

		if (!$project) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		if(!$project->isSharedTo($user)) {
            throw new AccessDeniedHttpException();
		}

		return $this->render('BtaskBoardBundle:Overview:project.html.twig', array(
			'project' => $project,
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
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findOneBySlug($workgroup_slug);

		if (!$workgroup) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		if (!$workgroup->hasOwner($user)) {
			// TODO: Return a notification
            throw new AccessDeniedHttpException();
		}

		// Get all projects which are in the workgroup
		$projects = $em->getRepository('BtaskBoardBundle:Project')->findBy(array('workgroup' => $workgroup->getId()));

		if (!$projects) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Return a JSON feed of workgroup templates
		$projects_template[] = array();
		foreach ($projects as $project) {
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

		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->find($id);

		// Check if the project exist and if the user can see the content of this project
		if (!$project && !$project->isSharedTo($user)) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Get the users which participe to the project
		$users = $em->getRepository('BtaskUserBundle:User')->findBy(array('project' => $project->getId()));

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
     * Display a form to create a project
     *
     */
	public function createProjectAction()
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();

		// TODO: Set a default selected workgroup
		// Get the workgroup where the use want to create his project
		//$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findOneBySlug($workgroup_slug);

		// Generate the form
	    $form = $this->createForm(new CollaborationType(), new Collaboration);
        $formHandler = new CollaborationHandler($form, $request, $em, $user);

        if($formHandler->process()) {
			// TODO: Return a notification
			return new Response(null, 200);
        }

		return $this->render('BtaskBoardBundle:Overview:form_create_project.html.twig', array(
			'form' => $form->createView(),
		));
	}

   /**
     * Display a form to update a project
     *
     */
	public function updateProjectAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->find($id);

		if(!$project) {
			throw new NotFoundHttpException();
		}

		// Check if the user is the owner of the project
		if(!$project->hasOwner($user)) {
            throw new AccessDeniedHttpException();
		}

		$collaboration = new Collaboration;
		$collaboration->setProject($project);

		// Generate the form
	    $form = $this->createForm(new CollaborationType(), $collaboration);
        $formHandler = new CollaborationHandler($form, $request, $em, $user);

        if($formHandler->process()) {
			// TODO: Return a notification
			return new Response(null, 200);
        }

		return $this->render('BtaskBoardBundle:Overview:form_create_project.html.twig', array(
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

		$em->remove($project);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
