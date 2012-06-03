<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Form\Type\TaskType;

class TaskController extends Controller
{
    /**
     * Display overdue, planned and done tasks of the logged user for a specific date
     *
     */
    public function showTasksByStateAction($state)
    {
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get tasks by their status (overdue, planned or done)
			$em = $this->getDoctrine()->getEntityManager();
			$tasks = $em->getRepository('BtaskBoardBundle:Item')->findTasksBy(array('state' => $state, 'executor' => $user));

			if (!$tasks) {
				return new Response(null, 204);
			}

			return $this->render('BtaskBoardBundle:Dashboard:task.html.twig', array(
				'tasks' => $tasks
			));
		}
    }

    /**
     * Display a form to udpate a task of the logged user
     *
     */
	public function updateTaskAction($id)
	{
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get the item
			$em = $this->getDoctrine()->getEntityManager();
			$item =  $em->getRepository('BtaskBoardBundle:Item')->find($id);

			if (!$item) {
	            throw new NotFoundHttpException();
	        }

	        // Check if the owner or executor item is the current logged user
			$owner = $item->getOwner();
			$executor = $item->getExecutor();

			if (($owner != $user) && ($executor != $user)) {
	            throw new AccessDeniedHttpException();
	        }

			// Generate the form
			// TODO: Move this logic below in a form handler

	        // Cast the item as task
			$taskType =  $this->getDoctrine()->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Task');
			$item->setType($taskType);
			$actionUrl = $this->generateUrl('BtaskBoardBundle_task_edit', array('id' => $id));
			$form = $this->createForm(new TaskType(), $item);

			$request = $this->get('request');
		    if( $request->getMethod() == 'POST' ) {
		        $form->bindRequest($request);

		        if( $form->isValid() ) {
		            $em = $this->getDoctrine()->getEntityManager();
		            $em->persist($item);
		            $em->flush();

		            return $this->redirect( $this->generateUrl('BtaskBoardBundle_board') );
		        }
		    }
			return $this->render('BtaskBoardBundle:Overview:form_task.html.twig', array(
				'form' => $form->createView(),
				'task' => $item,
				'actionUrl' => $actionUrl,
		    ));
		}
	}

    /**
     * Toggle the status of the current task (open or close)
     *
     */
    public function toggleStatusTaskAction($id, $status)
    {
		$request = $this->container->get('request');

		$em = $this->getDoctrine()->getEntityManager();
		$task = $em->getRepository('BtaskBoardBundle:Item')->find($id);

		if (!$task) {
			throw new NotFoundHttpException();
		}

		// Open or close the task only if his current status is different
		if($task->getStatus() != $status) {
			$task->setStatus($status);
			$em->persist($task);
			$em->flush();
		}

		// TODO: Return message if status passed is the current status or if the task has been updated
		return $this->redirect( $this->generateUrl('BtaskBoardBundle_board') );
    }

	/**
	 * Delete a task
	 *
	 */
	public function deleteTaskAction($id) {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the task
		$em = $this->getDoctrine()->getEntityManager();
		$task =  $em->getRepository('BtaskBoardBundle:Item')->find($id);

		if (!$task) {
			throw new NotFoundHttpException();
		}

		if (!$task->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		$em->remove($task);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
