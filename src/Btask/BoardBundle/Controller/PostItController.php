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

class PostItController extends Controller
{
	/**
	 * Display the post-it of the logged user
	 *
	 */
    public function showPostsItAction()
    {
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get all the post-it from the logged user
			$em = $this->getDoctrine()->getEntityManager();
			$postItType = $em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');
			$postsIt = $em->getRepository('BtaskBoardBundle:Item')->findBy(array('type' => $postItType->getId(), 'owner' => $user->getId()));

			if (!$postsIt) {
				return new Response(null, 204);
			}

			// Return a JSON feed of workgroup templates
			$post_it_template = array();
			foreach ($postsIt as $postIt) {
    			$post_it_template[] = $this->render('BtaskBoardBundle:Dashboard:post-it.html.twig', array('post_it' => $postIt))->getContent();
			}

			if(!$post_it_template) {
				// TODO: Return a notification
				return new Response(null, 204);
			}

			return new Response(json_encode($post_it_template), 200);
		}
		else {
            throw new NotFoundHttpException();
		}
    }

	/**
	 * Display a post-it of the logged user
	 *
	 */
    public function showPostItAction($id)
    {
		$request = $this->container->get('request');
		if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			// Get all the post-it from the logged user
			$em = $this->getDoctrine()->getEntityManager();
			$postItType = $em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');
			$postIt = $em->getRepository('BtaskBoardBundle:Item')->findBy(array( 'type' => $postItType->getId(), 'owner' => $user->getId()));

			if (!$postsIt) {
				return new Response(null, 204);
			}

			// Return a JSON feed of workgroup templates
			$post_it_template = array();
			foreach ($postsIt as $postIt) {
    			$post_it_template[] = $this->render('BtaskBoardBundle:Dashboard:post-it.html.twig', array('post_it' => $postIt))->getContent();
			}

			if(!$post_it_template) {
				// TODO: Return a notification
				return new Response(null, 204);
			}

			return new Response(json_encode($post_it_template), 200);
		}
		else {
            throw new NotFoundHttpException();
		}
    }

	/**
	 * Display a form to create a post-it
	 *
	 */
    public function createPostItAction()
	{
		$request = $this->container->get('request');
		//if($request->isXmlHttpRequest()) {

			$user = $this->get('security.context')->getToken()->getUser();

			$em = $this->getDoctrine()->getEntityManager();
			$postItType = $em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');

			// Create and set default value to the post-it
		    $item = new Item;
		    $item->setType($postItType);
		    $item->setOwner($user);

		    // Generate the form
		    // TODO: Move this logic below in a form handler
			$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_create');
		    $form = $this->createForm(new PostItType(), $item);

		    $request = $this->get('request');
		    if( $request->getMethod() == 'POST' ) {
		        $form->bindRequest($request);

		        if( $form->isValid() ) {
		            $em->persist($item);
		            $em->flush();

					// TODO: Return a notification
		            return new Response(null, 200);
		        }
		    }

		    return $this->render('BtaskBoardBundle:Dashboard:form_item.html.twig', array(
		        'form' => $form->createView(),
		       	'actionUrl' => $actionUrl,
		    ));
		/*}
		else {
            throw new NotFoundHttpException();
		}*/
	}

	/**
	 * Display a form to edit a post-it
	 *
	 */
    public function updatePostItAction($id)
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
		$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_update', array('id' => $id));
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
}
