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

    public function addPostItAction()
	{
	    $item = new Item;
	    $itemType =  $this->getDoctrine()->getRepository('BtaskDashboardBundle:ItemType')->find(1);
	    $item->setType($itemType);
	   	$item->setVersion(1);
	   	$item->setCurrent(true);
	   	$item->setcreatedAt(new \Datetime());
	   	$item->setValidationToken('123');
	    $form = $this->createForm(new PostItType, $item);

	    $request = $this->get('request');
	    if( $request->getMethod() == 'POST' )
	    {
	        $form->bindRequest($request);
	        if( $form->isValid() )
	        {
	            $em = $this->getDoctrine()->getEntityManager();
	            $em->persist($item);
	            $em->flush();

	            return $this->redirect( $this->generateUrl('BtaskDashboardBundle_homepage') );
	        }
	    }

	    return $this->render('BtaskDashboardBundle:Dashboard:post-it.html.twig', array(
	        'form' => $form->createView(),
	    ));
	}
}
