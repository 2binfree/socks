<?php

namespace sockBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('sockBundle:Default:index.html.twig');
    }
}
