<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 8/23/2016
 * Time: 1:00 AM
 */

namespace Yoda\EventBundle\Controller;
use Yoda\EventBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

class Controller extends BaseController {
    public function getSecurityContext()
    {
        return $this->get('security.authorization_checker');
    }
    public function enforceOwnerSecurity(Event $event)
    {
        $user = $this->getUser();

        if ($user != $event->getOwner()) {
            // if you're using 2.5 or higher
            throw $this->createAccessDeniedException('You are not the owner!!!');
            // throw new AccessDeniedException('You are not the owner!!!');
        }
    }
} 