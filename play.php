<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;



$loader = require __DIR__.'/app/autoload.php';
Debug::enable();
require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$kernel->boot();
$container=$kernel->getContainer();

// all the setupis done
use Yoda\UserBundle\Entity\User ;
$em=$container->get('doctrine')->getManager();
$user=$em->getRepository('YodaUserBundle:User')->findOneBy(

    array('username'=>'habib')
);
foreach ($user->getEvents() as $event){
    var_dump($event->getName());
}
