<?php

namespace Btask\BoardBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use Btask\BoardBundle\Entity\Collaboration;
use Btask\BoardBundle\Entity\Workgroup;
use Btask\UserBundle\Entity\User;

class CollaborationHandler
{
    protected $form;
    protected $request;
    protected $em;
    protected $user;
    protected $workgroup;

    public function __construct(Form $form, Request $request, EntityManager $em, User $user, Workgroup $workgroup)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->user    = $user;
        $this->workgroup    = $workgroup;
    }

    public function process()
    {
		if($this->request->getMethod() == 'POST') {
			$this->form->bindRequest($this->request);

			if($this->form->isValid()) {
				$this->onSuccess($this->form->getData());
				return true;
			}
		}

        return false;
    }

    public function onSuccess(Collaboration $collaboration)
    {
        // Assign the project to the current user
        $collaboration->setParticipant($this->user);
    	$collaboration->setOwner(true);
        $collaboration->setWorkgroup($this->workgroup);

        $this->em->persist($collaboration);
        $this->em->flush();
    }
}
