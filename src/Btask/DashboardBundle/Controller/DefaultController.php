<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function showPostItAction()
    {
    }

    public function createItemAction()
	{
	    $item = new Item;
    	$itemType =  $this->getDoctrine()->getRepository('BtaskDashboardBundle:ItemType')->find(1);
	    $item->setType($itemType);
	   	$item->setVersion(1);
	   	$item->setCurrent(true);
	   	$item->setcreatedAt(new \Datetime());
	   	$item->setValidationToken('123');
	    $form = $this->createForm(new PostItType(), $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' )
	    {
	        $form->bindRequest($request);
	        if( $form->isValid() )
	        {
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($item);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskDashboardBundle_board') );
	        }
	    }

	    return $this->render('BtaskDashboardBundle:Dashboard:add_item.html.twig', array(
	        'form' => $form->createView(),
	    ));
	}
}
