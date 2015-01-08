<?php

namespace MXT\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MXTCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
