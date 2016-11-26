<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 9/3/2016
 * Time: 12:11 PM
 */

namespace Yoda\EventBundle\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Response;
use Yoda\EventBundle\Reporting\EventReportManager;

class ReportController extends Controller
{
    /**
     * @Route("/events/report/recentlyUpdated.csv")
     */
    public function updateEventsAction(){
        $em=$em = $this->container->get('doctrine.orm.entity_manager');
        $em = $this->getDoctrine()->getManager();
        $eventReportManager = $this->container->get('event_report_manager');
        $content = $eventReportManager->getRecentlyUpdateReport();
        $response=new Response($content);
        $response->headers->set('Content-Type','Text/csv');
     return  $response;

    }

} 