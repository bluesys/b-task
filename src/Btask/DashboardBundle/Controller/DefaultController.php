<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function todayAction()
    {
        return $this->render('BtaskDashboardBundle:Default:today.html.twig');
    }
}
