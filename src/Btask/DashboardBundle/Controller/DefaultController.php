<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
			$posts_it = $em->getRepository('BtaskDashboardBundle:Item')->findByItemType('post-it');

			if ($posts_it) {
		        return $this->render('BtaskDashboardBundle:Dashboard:post-it.html.twig', array(
		            'posts_it' => $posts_it
	            ));
			}
		}
    }

    public function newItemAction()
	{
	    $item = new Item;
    	$itemType =  $this->getDoctrine()->getRepository('BtaskDashboardBundle:ItemType')->find(1);
	    $item->setType($itemType);
		$actionUrl = $this->generateUrl('BtaskDashboardBundle_item_add');
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' ) {
	        $form->bindRequest($request);

	        if( $form->isValid() ) {
		        $item->setcreatedAt(new \Datetime());
		        $item->setValidationToken('123');
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($item);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskDashboardBundle_board') );
	        }
	    }

	    return $this->render('BtaskDashboardBundle:Dashboard:add_item.html.twig', array(
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
	    return $this->render('BtaskDashboardBundle:Dashboard:add_item.html.twig', array(
	        'form' => $form->createView(),
	       	'item' => $item,
	       	'actionUrl' => $actionUrl,
	    ));
	}
}
