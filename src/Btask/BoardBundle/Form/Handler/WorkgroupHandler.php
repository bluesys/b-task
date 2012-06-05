<?php

namespace Btask\BoardBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use Btask\BoardBundle\Entity\Workgroup;
use Btask\UserBundle\Entity\User;

class WorkgroupHandler
{
    protected $form;
    protected $request;
    protected $em;
    protected $user;

    public function __construct(Form $form, Request $request, EntityManager $em, User $user)
    {
        $this->form    = $form;
        $this->request = $request;
        $this->em      = $em;
        $this->user    = $user;
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

    public function onSuccess(Workgroup $workgroup)
    {
        // Assign the workgroup to the current user
    	$workgroup->setOwner($this->user);

        $this->em->persist($workgroup);
        $this->em->flush();
    }
}
