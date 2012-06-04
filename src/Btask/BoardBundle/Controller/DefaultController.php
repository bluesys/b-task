<?php

namespace Btask\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BtaskBoardBundle::layout.html.twig');
    }

    public function todayAction()
    {
        return $this->render('BtaskBoardBundle:Default:today.html.twig');
    }

    public function ProjectAction()
    {
        return $this->render('BtaskBoardBundle:Default:project.html.twig');
    }
}
