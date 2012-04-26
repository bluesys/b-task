<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction()
    {
        return $this->render('BtaskDashboardBundle:Default:index.html.twig');
    }

    public function prototypeAction()
    {
        return $this->render('BtaskDashboardBundle:Default:index.html.twig');
    }
}
