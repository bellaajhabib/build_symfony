<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 9/4/2016
 * Time: 5:22 PM
 */

namespace Yoda\EventBundle\Twig;


use Symfony\Component\Validator\Constraints\DateTime;
use Yoda\EventBundle\Util\DateUtil;

class EventExtension extends  \Twig_Extension{
    public function getName()
    {
        return 'event';
    }
public function getFilters(){

    return array(new \Twig_SimpleFilter('ago',array($this,'calculateAgo')));
}
    public function calculateAgo(\DateTime $dt){

        return DateUtil::ago($dt);
    }
}