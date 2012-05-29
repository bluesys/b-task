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

	public function showWorkgroupAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get workgroup
			// TODO: Check if the current user is authorized to see the workgroup
			$em = $this->getDoctrine()->getEntityManager();
			$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->find($id);

			if (!$workgroup) {
				throw new NotFoundHttpException();
			}

			return $this->render('BtaskBoardBundle:Overview:workgroup.html.twig', array(
				'workgroup' => $workgroup,
			));
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
			$workgroups = $em->getRepository('BtaskBoardBundle:Workgroup')->findBy(array('user' => $user->getId()));

		    $form = $this->createForm(new WorkgroupType(), new Workgroup);

			if (!$workgroups) {
				// TODO: Return a notification
				return new Response(null, 204);
			}

			return $this->render('BtaskBoardBundle:Overview:workgroups.html.twig', array(
				'workgroups' => $workgroups,
				'form' => $form->createView(),
			));
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

		            // TODO: Return a notification
					return new Response(null, 200);
		        }
		    }

			return $this->render('BtaskBoardBundle:Overview:form_create_workgroup.html.twig', array(
				'form' => $form->createView(),
			));
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
			$workgroup = $em->getRepository('BtaskBoardBundle:Workgroup')->findBy(array('id' => $id, 'user' => $user->getId()));

			if (!$workgroup) {
				throw new NotFoundHttpException();
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
	}
}
