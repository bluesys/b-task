<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Btask\BoardBundle\Entity\Item;

class NoteController extends Controller
{
	/**
     * Display all notes by project
     *
     */
	public function showNotesByProjectAction($project_slug) {

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
	public function showNotesAction() {

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
	 * Delete a note
	 *
	 */
	public function deleteNoteAction($id) {

		$request = $this->container->get('request');
		if(!$request->isXmlHttpRequest()) {
			throw new NotFoundHttpException();
		}

		$user = $this->get('security.context')->getToken()->getUser();

		$em = $this->getDoctrine()->getEntityManager();
		// TODO: Check if it's a note
		$note = $em->getRepository('BtaskBoardBundle:Item')->find($id);

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
