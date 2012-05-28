<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
