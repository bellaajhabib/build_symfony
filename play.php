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
use Yoda\EventBundle\Entity\Event;
$event=new Event();
$event->setName('habib');
$event->setLocation('Dethstar');
$event->setTime(new \DateTime());
$event->setDetails('Has Darth HATE surprise !');
$em=$container->get('doctrine')->getManager();
$em->persist($event);
$em->flush();
$container->set('request',$request);
    // all the setup is done !!! Woo hoo
$tempating=$container->get('templating');
$show=$tempating->render('EventBundle:Default:index.html.twig');
echo $show;