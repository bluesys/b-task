<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Workgroup;
use Btask\BoardBundle\Entity\WorkgroupCollaboration;
use Btask\BoardBundle\Form\Type\WorkgroupType;
use Btask\BoardBundle\Form\Handler\WorkgroupHandler;

class WorkgroupController extends Controller
{

	/**
	 * Display a workgroup
	 *
	 */
	public function showWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get workgroup
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

		if (!$workgroup) {
			throw new NotFoundHttpException();
		}

		// Check if the workgroup is owned by the current logged user
		if(!$workgroup->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		return $this->render('BtaskBoardBundle:Workgroup:workgroup.html.twig', array(
			'workgroup' => $workgroup,
		));
	}


   /**
     * Display workgroups of the current logged user
     *
     */
	public function showWorkgroupsAction()
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get workgroups
		$em = $this->getDoctrine()->getEntityManager();
		$workgroups = $em->getRepository('BtaskBoardBundle:Workgroup')->findByOwner($user->getId());

		if (!$workgroups) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Return a JSON feed of workgroup templates
		$workgroups_template[] = array();
		foreach ($workgroups as $workgroup) {
	    	$workgroups_template[] = $this->render('BtaskBoardBundle:Workgroup:workgroup.html.twig', array('workgroup' => $workgroup))->getContent();
		}

		$response = new Response(json_encode($workgroups_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}


   /**
     * Display a form to create a workgroup
     *
     */
	public function createWorkgroupAction()
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Generate the form
		$workgroup = new Workgroup;
		$form = $this->createForm(new WorkgroupType($user), $workgroup);
		$formHandler = new WorkgroupHandler($form, $request, $em, $user);

		if($formHandler->process()) {
			return $this->render('BtaskBoardBundle:Workgroup:workgroup.html.twig', array(
				'workgroup' => $workgroup,
			));
		}

		return $this->render('BtaskBoardBundle:Workgroup:form_create_workgroup.html.twig', array(
			'form' => $form->createView(),
		));
	}


	/**
     * Display a form to edit a workgroup
     *
     */
	public function updateWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the workgroup
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

		if (!$workgroup) {
			throw new NotFoundHttpException();
		}

		// Check if the workgroup is owned by the current logged user
		if (!$workgroup->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		$form = $this->createForm(new WorkgroupType($user), $workgroup);
		$formHandler = new WorkgroupHandler($form, $request, $em, $user);

		if($formHandler->process()) {
			return $this->render('BtaskBoardBundle:Workgroup:workgroup.html.twig', array(
				'workgroup' => $workgroup,
			));
		}

		return $this->render('BtaskBoardBundle:Workgroup:form_update_workgroup.html.twig', array(
			'form' => $form->createView(),
			'workgroup' => $workgroup,
		));
	}


	/**
     * Delete a workgroup
     *
     */
	public function deleteWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the workgroup
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

		if (!$workgroup) {
			throw new NotFoundHttpException();
		}

		// Check if the workgroup is owned by the current logged user
		if(!$workgroup->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		$em->remove($workgroup);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
