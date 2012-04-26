<?php

namespace Btask\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BtaskUserBundle:Default:index.html.twig', array('name' => $name));
    }
}
