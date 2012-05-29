<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Workgroup;
use Btask\BoardBundle\Entity\UserWorkgroup;
use Btask\BoardBundle\Form\Type\WorkgroupType;

class WorkgroupController extends Controller
{

	public function showWorkgroupAction($id) {

		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get workgroups
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

		if (!$workgroup) {
			throw new NotFoundHttpException();
		}

		if ($user != $workgroup->getUsersWorkgroups->getUser()) {
			throw new AccessDeniedHttpException();
		}

		return $this->render('BtaskBoardBundle:Overview:workgroup.html.twig', array(
			'workgroup' => $workgroup,
		));
	}

   /**
     * Display workgroups of the current logged user
     *
     */
	public function showWorkgroupsAction() {
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get workgroups
		$em = $this->getDoctrine()->getEntityManager();
		$workgroups = $em->getRepository('BtaskBoardBundle:Workgroup')->findByUser($user);

	    $form = $this->createForm(new WorkgroupType(), new Workgroup);

		if (!$workgroups) {
			return new Response(null, 204);
		}

		return $this->render('BtaskBoardBundle:Overview:workgroups.html.twig', array(
			'workgroups' => $workgroups,
			'form' => $form->createView(),
		));
	}

   /**
     * Display a form to create a workgroup
     *
     */
	public function createWorkgroupAction() {

		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		$workgroup = new Workgroup;

	    // Generate the form
	    // TODO: Move this logic below in a form handler
	    $form = $this->createForm(new WorkgroupType(), $workgroup);

		$request = $this->container->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
				$em = $this->getDoctrine()->getEntityManager();
	        	$em->persist($workgroup);
	            $em->flush();

				$userWorkgroup = new UserWorkgroup;
				$userWorkgroup->setUser($user);
				$userWorkgroup->setWorkgroup($workgroup);
				$userWorkgroup->setOwner(true);

	            $em->persist($userWorkgroup);
	            $em->flush();

				return new Response(null, 200);
	        }
	    }

		return $this->render('BtaskBoardBundle:Overview:form_create_workgroup.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/**
     * Display a form to edit a workgroup
     *
     */
	public function updateWorkgroupAction($id) {
		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get the workgroup
		$em = $this->getDoctrine()->getEntityManager();
		$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

		if (!$workgroup) {
			throw new NotFoundHttpException();
		}

	    $form = $this->createForm(new WorkgroupType(), $workgroup);

		// TODO: Check if owner (isOwner)
		$request = $this->container->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
				$em = $this->getDoctrine()->getEntityManager();
	        	$em->persist($workgroup);
	            $em->flush();

				return new Response(null, 200);
	        }
	    }

		return $this->render('BtaskBoardBundle:Overview:form_update_workgroup.html.twig', array(
			'form' => $form->createView(),
			'workgroup' => $workgroup,
		));
	}
}
