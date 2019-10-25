<?php

namespace GenSys\GenerateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GenSysGenerateBundle:Default:index.html.twig');
    }
}
