<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Entity\ItemType;
use Btask\BoardBundle\Form\Type\PostItType;
use Btask\BoardBundle\Form\Type\TaskType;
use Btask\BoardBundle\Form\Type\NoteType;

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
     * Returns all the post-it
     *
     */
    public function showPostItAction()
    {
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException();
		}

		// Get the current logged user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get all the post-it from the logged user
		$em = $this->getDoctrine()->getEntityManager();
		$postItType = $em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');
		$postsIt = $em->getRepository('BtaskBoardBundle:Item')->findBy(array('type' => $postItType->getId(), 'owner' => $user->getId()));

		if (!$postsIt) {
			return new Response(null, 204);
		}

        return $this->render('BtaskBoardBundle:Dashboard:post-it.html.twig', array(
            'posts_it' => $postsIt
        ));
    }

    public function newPostItAction()
	{
		// Get the current logged user
		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$postItType = $em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');

		// Create and set default value to the post-it
	    $item = new Item;
	    $item->setType($postItType);
	    $item->setOwner($user);

	    // Generate the form
	    // TODO: Move this logic below in a form handler
		$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_add');
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
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

    public function editPostItAction($id)
	{
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		// Get the item
		$em = $this->getDoctrine()->getEntityManager();
		$item =  $em->getRepository('BtaskBoardBundle:Item')->find($id);
		
		if (!$item) {
            throw new NotFoundHttpException();
        }

		// Generate the form
		// TODO: Move this logic below in a form handler
		$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_edit', array('id' => $id));
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);
	        
	        if( $form->isValid() ) {
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

     /**
     * Returns overdue, planned and done tasks for a specific date
     *
     * @param string status
     * @param date date
     */
    public function showTasksByStateAction($state)
    {
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		// Get the current logged user
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

    /**
     * Toggle the status of the current task (open or close)
     *
     * @param int $id
     */
    public function toggleStatusTaskAction($id, $status)
    {
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

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

	public function editTaskAction($id)
	{
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		// Get the current user
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

	public function editNoteAction($id)
	{
		$request = $this->container->get('request');

		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		// Get the current user
		$user = $this->get('security.context')->getToken()->getUser();

		// Get the item
		$em = $this->getDoctrine()->getEntityManager();
		$item =  $em->getRepository('BtaskBoardBundle:Item')->find($id);

		if (!$item) {
            throw new NotFoundHttpException();
        }

		// Generate the form
		// TODO: Move this logic below in a form handler

        // Cast the item as task
		$noteType =  $this->getDoctrine()->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Note');
		$item->setType($noteType);
		$actionUrl = $this->generateUrl('BtaskBoardBundle_note_edit', array('id' => $id));
		$form = $this->createForm(new NoteType(), $item);

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
		return $this->render('BtaskBoardBundle:Overview:form_note.html.twig', array(
			'form' => $form->createView(),
			'note' => $item,
			'actionUrl' => $actionUrl,
	    ));
	}
}
