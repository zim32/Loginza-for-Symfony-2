<?php

namespace Zim32\LoginzaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('Zim32LoginzaBundle:Default:index.html.twig', array('name' => $name));
    }
}
