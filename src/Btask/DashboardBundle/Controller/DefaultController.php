<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BtaskDashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
