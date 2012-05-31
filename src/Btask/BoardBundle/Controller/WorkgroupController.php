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

class WorkgroupController extends Controller
{

	/**
	 * Display a workgroup
	 *
	 */
	public function showWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

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

			return $this->render('BtaskBoardBundle:Overview:workgroup.html.twig', array(
				'workgroup' => $workgroup,
			));
		}
		else {
			throw new NotFoundHttpException();
		}
	}


   /**
     * Display workgroups of the current logged user
     *
     */
	public function showWorkgroupsAction()
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get workgroups
			$em = $this->getDoctrine()->getEntityManager();
			$workgroups = $em->getRepository('BtaskBoardBundle:Workgroup')->findBy(array('owner' => $user->getId()));

			if (!$workgroups) {
				// TODO: Return a notification
				return new Response(null, 204);
			}

			// Return a JSON feed of workgroup templates
			$workgroups_template[] = array();
			foreach ($workgroups as $workgroup) {
		    	$workgroups_template[] = $this->render('BtaskBoardBundle:Overview:workgroup.html.twig', array('workgroup' => $workgroup))->getContent();
			}

			if(!$workgroups_template) {
				// TODO: Return a notification
				return new Response(null, 204);
			}

			return new Response(json_encode($workgroups_template), 200);
		}
		else {
			throw new NotFoundHttpException();
		}
	}


   /**
     * Display a form to create a workgroup
     *
     */
	public function createWorkgroupAction()
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Generate the form
			// TODO: Move this logic below in a form handler
			$workgroup = new Workgroup;
		    $form = $this->createForm(new WorkgroupType(), $workgroup);

		    if($request->getMethod() == 'POST') {
		        $form->bindRequest($request);

		        if( $form->isValid() ) {
					$em = $this->getDoctrine()->getEntityManager();
		        	$em->persist($workgroup);
		            $em->flush();

					$workgroupCollaboration = new WorkgroupCollaboration;
					$workgroupCollaboration->setParticipant($user);
					$workgroupCollaboration->setWorkgroup($workgroup);
					$workgroupCollaboration->setOwner(true);
					$workgroupCollaboration->setShared(false);

		            $em->persist($workgroupCollaboration);
		            $em->flush();

		            // TODO: Return a notification
					return new Response(null, 200);
		        }
		    }

			return $this->render('BtaskBoardBundle:Overview:form_create_workgroup.html.twig', array(
				'form' => $form->createView(),
			));
		}
		else {
			throw new NotFoundHttpException();
		}
	}


	/**
     * Display a form to edit a workgroup
     *
     */
	public function updateWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

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

		    $form = $this->createForm(new WorkgroupType(), $workgroup);

			// Generate the form
			// TODO: Move this logic below in a form handler
			$request = $this->container->get('request');
		    if( $request->getMethod() == 'POST' ) {
		        $form->bindRequest($request);

		        if( $form->isValid() ) {
					$em = $this->getDoctrine()->getEntityManager();
		        	$em->persist($workgroup);
		            $em->flush();

					// TODO: Return a notification
					return new Response(null, 200);
		        }
		    }

			return $this->render('BtaskBoardBundle:Overview:form_update_workgroup.html.twig', array(
				'form' => $form->createView(),
				'workgroup' => $workgroup,
			));
		}
		else {
			throw new NotFoundHttpException();
		}
	}


	/**
     * Delete a workgroup
     *
     */
	public function deleteWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

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
		else {
			throw new NotFoundHttpException();
		}
	}
}
