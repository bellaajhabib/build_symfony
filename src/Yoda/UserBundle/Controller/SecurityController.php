<?php

namespace Yoda\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
class SecurityController extends Controller
{
    /**
     * @Route("/login",name="login_form")
     * @Template()
     */
    public function loginAction()
    {
        return  array(
            // ...
        );
    }

}
