<?php

namespace Btask\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('BtaskDashboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
