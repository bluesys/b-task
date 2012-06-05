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
use Btask\BoardBundle\Form\Handler\PostItHandler;

class PostItController extends Controller
{
	/**
	 * Display the post-it of the logged user
	 *
	 */
    public function showPostsItAction()
    {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get all the post-it from the logged user
		$em = $this->getDoctrine()->getEntityManager();
		$postsIt = $em->getRepository('BtaskBoardBundle:Item')->findPostItBy(array('owner' => $user->getId(), 'status' => true));

		if (!$postsIt) {
			// TODO: Return a notification
			return new Response(null, 204);
		}

		// Return a JSON feed of posit-it templates
		$post_it_template = array();
		foreach ($postsIt as $postIt) {
			$post_it_template[] = $this->render('BtaskBoardBundle:PostIt:post-it.html.twig', array('post_it' => $postIt))->getContent();
		}

		$response = new Response(json_encode($post_it_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
    }


	/**
	 * Display a post-it of the logged user
	 *
	 */
    public function showPostItAction($id)
    {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the post-it
		$em = $this->getDoctrine()->getEntityManager();
		$postIt = $em->getRepository('BtaskBoardBundle:Item')->findOnePostItBy(array('id' => $id));

		if (!$postIt) {
			return new Response(null, 204);
		}

		if (!$postIt->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		return $this->render('BtaskBoardBundle:PostIt:post-it.html.twig', array(
			'post_it' => $postIt
		));
    }


	/**
	 * Display a form to create a post-it
	 *
	 */
    public function createPostItAction()
	{
		$request = $this->container->get('request');
		// Check if it is an Ajax request
		if(!$request->isXmlHttpRequest()) {
			throw new MethodNotAllowedHttpException(array('Ajax request'));
		}

		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getEntityManager();

	    // Generate the form
		$postIt = new Item;
        $postIt->setStatus(true);

		$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_create');
		$form = $this->createForm(new PostItType($user), $postIt);
		$formHandler = new PostItHandler($form, $request, $em, $user);

        if($formHandler->process()) {
			// Return the created post-it or null if is a task or a note
			if($postIt->getType()->getName() == 'Post-it') {
				return $this->render('BtaskBoardBundle:PostIt:post-it.html.twig', array(
					'post_it' => $postIt,
				));
			}

			return new Response(null, 200);
    	}

	    return $this->render('BtaskBoardBundle:PostIt:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'actionUrl' => $actionUrl,
	    ));
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

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the item
		$em = $this->getDoctrine()->getEntityManager();
		$postIt =  $em->getRepository('BtaskBoardBundle:Item')->findOnePostItBy(array('id' => $id));

		if (!$postIt) {
            throw new NotFoundHttpException();
        }

		// Generate the form
		$actionUrl = $this->generateUrl('BtaskBoardBundle_post_it_update', array('id' => $id));
		$form = $this->createForm(new PostItType($user), $postIt);
		$formHandler = new PostItHandler($form, $request, $em, $user);

        if($formHandler->process()) {
			// Return the created post-it or null if is a task or a note
			if($postIt->getType()->getName() == 'Post-it') {
				return $this->render('BtaskBoardBundle:PostIt:post-it.html.twig', array(
					'post_it' => $postIt,
				));
			}

			return new Response(null, 200);
    	}

	    return $this->render('BtaskBoardBundle:PostIt:form_item.html.twig', array(
	        'form' => $form->createView(),
	       	'item' => $postIt,
	       	'actionUrl' => $actionUrl,
	    ));
	}


	/**
     * Close the current postit (status = false)
     *
     */
    public function closePostItAction($id)
    {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$postIt = $em->getRepository('BtaskBoardBundle:Item')->findOnePostItBy(array('id' => $id));

		if (!$postIt) {
			throw new NotFoundHttpException();
		}

		if (!$postIt->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		// Set postit status to false
		$postIt->setStatus(false);
		$em->persist($postIt);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
    }



	/**
	 * Delete a post-it
	 *
	 */
	public function deletePostItAction($id) {
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get the post-it
		$em = $this->getDoctrine()->getEntityManager();
		$postIt = $em->getRepository('BtaskBoardBundle:Item')->findOnePostItBy(array('id' => $id));

		if (!$postIt) {
			throw new NotFoundHttpException();
		}

		if (!$postIt->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		$em->remove($postIt);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
