<?php

namespace Yoda\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/aaa")
     */
    public function indexAction()
    {
        return $this->render('YodaUserBundle:Default:index.html.twig');
    }
}
