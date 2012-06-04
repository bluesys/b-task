<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Form\Type\NoteType;
use Btask\BoardBundle\Form\Handler\NoteHandler;

class NoteController extends Controller
{
	/**
     * Display all notes by project
     *
     */
	public function showNotesByProjectAction($project_slug)
	{

		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$project = $em->getRepository('BtaskBoardBundle:Project')->findOneBySlug($project_slug);

		if (!$project && !$project->isSharedTo($user)) {
			throw new NotFoundHttpException();
		}

		// Get notes by project
		$notes = $em->getRepository('BtaskBoardBundle:Item')->findNotesBy(array('project' => $project->getId()));

		if (!$notes) {
			throw new NotFoundHttpException();
		}

		// Return a JSON feed of notes templates
		$notes_template = array();
		foreach ($notes as $note) {
			$notes_template[] = $this->render('BtaskBoardBundle:Dashboard:note.html.twig', array('note' => $note))->getContent();
		}

		$response = new Response(json_encode($notes_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}


	/**
     * Display all notes
     *
     */
	public function showNotesAction()
	{

		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		// Get notes
		$em = $this->getDoctrine()->getEntityManager();
		$notes = $em->getRepository('BtaskBoardBundle:Item')->findNotesBy(array('user' => $user->getId()));

		if (!$notes) {
			throw new NotFoundHttpException();
		}

		// Return a JSON feed of notes templates
		$notes_template = array();
		foreach ($notes as $note) {
			$notes_template[] = $this->render('BtaskBoardBundle:Dashboard:note.html.twig', array('note' => $note))->getContent();
		}

		$response = new Response(json_encode($notes_template), 200);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}


	/**
	 * Display a note
	 *
	 */
	public function showNoteAction($id)
	{

		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$note = $em->getRepository('BtaskBoardBundle:Item')->findOneNoteBy(array('id' => $id));

		if(!$note) {
			throw new NotFoundHttpException();
		}

		if (!$note->isSharedTo($user)) {
			throw new AccessDeniedHttpException();
		}

		return $this->render('BtaskBoardBundle:Dashboard:note.html.twig', array(
			'note' => $note,
		));
	}


	/**
	 * Update a note
	 *
	 */
	public function updateNoteAction($id)
	{
		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$note = $em->getRepository('BtaskBoardBundle:Item')->findOneNoteBy(array('id' => $id));

		if(!$note) {
			throw new NotFoundHttpException();
		}

		if (!$note->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		// Generate the form
	    $form = $this->createForm(new NoteType($user), $note);
        $formHandler = new NoteHandler($form, $request, $em, $user);

        if($formHandler->process()) {
			// TODO: Return a notification
			return new Response(null, 200);
        }

		return $this->render('BtaskBoardBundle:Dashboard:form_update_note.html.twig', array(
			'form' => $form->createView(),
			'note' => $note,
		));
	}


	/**
	 * Delete a note
	 *
	 */
	public function deleteNoteAction($id)
	{

		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		$note = $em->getRepository('BtaskBoardBundle:Item')->findOneNoteBy(array('id' => $id));

		if(!$note) {
			throw new NotFoundHttpException();
		}

		if (!$note->hasOwner($user)) {
			throw new AccessDeniedHttpException();
		}

		$em->remove($note);
		$em->flush();

		// TODO: Return a notification
		return new Response(null, 200);
	}
}
