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

        // Get item types
        $postItType = $this->em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Post-it');
        $taskType = $this->em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Task');
        $noteType = $this->em->getRepository('BtaskBoardBundle:ItemType')->findOneByName('Note');

        // If a planned date was entered, set item as task
        if($item->getPlanned()) {
            $item->setType($taskType);
        }
        // If a detail was entered, set item as note
        elseif ($item->getDetail()) {
            $item->setType($noteType);
        }
        // else, set item as post-it
        else {
            $item->setType($postItType);
        }

        $this->em->persist($item);
        $this->em->flush();
    }
}
