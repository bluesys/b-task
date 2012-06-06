<?php

namespace Btask\BoardBundle\Form\Handler;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

use Btask\BoardBundle\Entity\Collaboration;
use Btask\BoardBundle\Entity\Item;
use Btask\BoardBundle\Entity\ItemType;
use Btask\UserBundle\Entity\User;

class PostItHandler
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

    public function onSuccess(Item $item)
    {
        // Assign the the item to the connected user
        $item->setOwner($this->user);

        // If this task is not assigned, assign-it to the current user
        if(!$item->getExecutor()) {
            $item->setExecutor($this->user);
        }

        // If there is no type assign it the default
        if(!$item->getType()) {
            $postItType = $this->em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');
            $item->setType($postItType);
        }

        $this->em->persist($item);
        $this->em->flush();
    }
}
