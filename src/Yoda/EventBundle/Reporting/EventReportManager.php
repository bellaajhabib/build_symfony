<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 9/3/2016
 * Time: 3:31 PM
 */

namespace Yoda\EventBundle\Reporting;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Router;
use Symfony\Component\Translation\Loader;
use Symfony\Component\Validator\Constraints\EmailValidator;
class EventReportManager {
    private $em; protected $request;protected $router;
    public function __construct(EntityManager $em,EmailValidator $constraints ,Router $router){
        $this->em=$em;
        $this->router=$router;

    }


    public function setRequest(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();


    }
    public function  getRecentlyUpdateReport(){


        $events =  $this->em->getRepository('EventBundle:Event')
            ->getRecentlyUpdatedEvents();

        $rows = array();
        foreach ($events as $event) {
            $data = array(   $event->getId(),
                              $event->getName(),
                             $event->getTime()->format('Y-m-d H:i:s'),
                                $this->router->generate(
                                    'event_show',
                                    array('slug' => $event->getSlug()),
                                    true
                                )

                            );

            $rows[] = implode(',', $data);
        }

        return implode("\n", $rows);

    }

} 