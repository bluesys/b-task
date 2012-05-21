<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Btask\DashboardBundle\Entity\Item;
use Btask\DashboardBundle\Entity\ItemType;
use Btask\DashboardBundle\Form\Type\PostItType;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BtaskDashboardBundle::layout.html.twig');
    }

    public function todayAction()
    {
        return $this->render('BtaskDashboardBundle:Default:today.html.twig');
    }

    public function showBoardAction()
    {
        return $this->render('BtaskDashboardBundle::layout.html.twig');
    }

    /**
     * Return all the post-it
     *
     */
    public function showPostItAction()
    {
    	$request = $this->container->get('request');

   		if($request->isXmlHttpRequest()) {
   			
   			// Get all the post-it
			$em = $this->getDoctrine()->getEntityManager();
			$posts_it = $em->getRepository('BtaskDashboardBundle:Item')->findByItemType('Post-it');

			if ($posts_it) {
		        return $this->render('BtaskDashboardBundle:Dashboard:post-it.html.twig', array(
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
    public function showTasksByStateAction($state, $date)
    {
		$request = $this->container->get('request');
		$em = $this->getDoctrine()->getEntityManager();

		// Get tasks by their status (overdue, planned or done)
		$tasks = $em->getRepository('BtaskDashboardBundle:Item')->findTasksBy(array('state' => $state), $date);

		if (!$tasks) {
			return new Response(null);
		}

        return $this->render('BtaskDashboardBundle:Dashboard:task.html.twig', array(
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
		$task = $em->getRepository('BtaskDashboardBundle:Item')->find($id);

		if (!$task) {
            throw new NotFoundHttpException();
		}

		if($task->getStatus() != $status) {
			// Open or close the task
			$task->setStatus($status);
	        $em->persist($task);
        	$em->flush();
		}
		// TODO: Return message if status passed is the current status
        return $this->redirect( $this->generateUrl('BtaskDashboardBundle_board') );
    }

    public function newItemAction()
	{
	    $item = new Item;
    	$itemType =  $this->getDoctrine()->getRepository('BtaskDashboardBundle:ItemType')->findOneByName('Post-it');
	    $item->setType($itemType);
		$actionUrl = $this->generateUrl('BtaskDashboardBundle_item_add');
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($item);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskDashboardBundle_board') );
	        }
	    }

	    return $this->render('BtaskDashboardBundle:Dashboard:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'actionUrl' => $actionUrl,
	    ));
	}

    public function editItemAction($id = null)
	{

    	$item =  $this->getDoctrine()->getRepository('BtaskDashboardBundle:Item')->find($id);
		
		if (!$item) {
            throw new NotFoundHttpException();
        }

		$actionUrl = $this->generateUrl('BtaskDashboardBundle_item_edit', array('id' => $id));
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);
	        
	        if( $form->isValid() ) {
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($item);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskDashboardBundle_board') );
	        }
	    }
	    return $this->render('BtaskDashboardBundle:Dashboard:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'item' => $item,
	       	'actionUrl' => $actionUrl,
	    ));
	}
}
