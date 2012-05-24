<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Entity\ItemType;
use Btask\BoardBundle\Form\Type\PostItType;
use Btask\BoardBundle\Form\Type\TaskType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BtaskBoardBundle::layout.html.twig');
    }

    public function todayAction()
    {
        return $this->render('BtaskBoardBundle:Default:today.html.twig');
    }

    public function showBoardAction()
    {
        return $this->render('BtaskBoardBundle::layout.html.twig');
    }

    /**
     * Return all the post-it
     *
     */
    public function showPostItAction()
    {
    	$request = $this->container->get('request');

   		if($request->isXmlHttpRequest()) {
			// Get the current user
			$user = $this->get('security.context')->getToken()->getUser();

			// Get all the post-it of the connected user
			$em = $this->getDoctrine()->getEntityManager();
			$posts_it = $em->getRepository('BtaskBoardBundle:Item')->findPostItBy(array('owner' => $user));

			if ($posts_it) {
		        return $this->render('BtaskBoardBundle:Dashboard:post-it.html.twig', array(
		            'posts_it' => $posts_it
	            ));
			}
			else {
				return new Response(null);
			}
		}
    }

     /**
     * Returns overdue, planned and done tasks for a specific date
     *
     * @param string status
     * @param date date
     */
    public function showTasksByStateAction($state)
    {
		// Get the current user
    	$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();

		// Get tasks by their status (overdue, planned or done)
		$tasks = $em->getRepository('BtaskBoardBundle:Item')->findTasksBy(array('state' => $state, 'executor' => $user));

		if (!$tasks) {
			return new Response(null);
		}

        return $this->render('BtaskBoardBundle:Dashboard:task.html.twig', array(
            'tasks' => $tasks
        ));
    }

    /**
     * Toggle the status of the current task (open or close)
     *
     * @param int $id
     */
    public function toggleStatusTaskAction($id, $status)
    {
    	$request = $this->container->get('request');

		$em = $this->getDoctrine()->getEntityManager();
		$task = $em->getRepository('BtaskBoardBundle:Item')->find($id);

		if (!$task) {
            throw new NotFoundHttpException();
		}

		if($task->getStatus() != $status) {
			// Open or close the task
			$task->setStatus($status);
	        $em->persist($task);
        	$em->flush();
		}
		// TODO: Return message if status passed is the current status or if the task has been updated
        return $this->redirect( $this->generateUrl('BtaskBoardBundle_board') );
    }

    public function newItemAction()
	{
		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		$itemType =  $this->getDoctrine()->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');

		// Create and set default value to the item
	    $item = new Item;
	    $item->setType($itemType);
	    $item->setOwner($user);

		$actionUrl = $this->generateUrl('BtaskBoardBundle_item_add');
	    $form = $this->createForm(new PostItType(), $item);

	    // TODO: Move this logic in a form handler
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

	    return $this->render('BtaskBoardBundle:Dashboard:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'actionUrl' => $actionUrl,
	    ));
	}

    public function editItemAction($id)
	{

    	$item =  $this->getDoctrine()->getRepository('BtaskBoardBundle:Item')->find($id);
		
		if (!$item) {
            throw new NotFoundHttpException();
        }

		$actionUrl = $this->generateUrl('BtaskBoardBundle_item_edit', array('id' => $id));
	    $form = $this->createForm(new PostItType(), $item);

	    // TODO: Move this logic in a form handler
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
	    return $this->render('BtaskBoardBundle:Dashboard:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'item' => $item,
	       	'actionUrl' => $actionUrl,
	    ));
	}

    public function editTaskAction($id)
	{
		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get the task
		$task =  $this->getDoctrine()->getRepository('BtaskBoardBundle:Item')->findOneBy(array('id' => $id));

		if (!$task) {
            throw new NotFoundHttpException();
        }

        // Get the owner and the executor the the task
		$owner = $task->getOwner();
		$executor = $task->getExecutor();

		if (($owner != $user) && ($executor != $user)) {
            throw new NotFoundHttpException();
        }

		$actionUrl = $this->generateUrl('BtaskBoardBundle_task_edit', array('id' => $id));
		$form = $this->createForm(new TaskType(), $task);

		// TODO: Move this logic in a form handler
		$request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($task);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskBoardBundle_board') );
	        }
	    }
		return $this->render('BtaskBoardBundle:Dashboard:form_task.html.twig', array(
			'form' => $form->createView(),
			'task' => $task,
			'actionUrl' => $actionUrl,
	    ));
	}
}
