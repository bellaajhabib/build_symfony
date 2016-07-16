<?php
/**
 * Created by PhpStorm.
 * User: habib
 * Date: 6/30/2016
 * Time: 1:41 AM
 */
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Yoda\EventBundle\Entity\Event;
class LoadEvents implements FixtureInterface {
public function load(ObjectManager $manager){
$event1= new Event();
$event1->setName('Darth\'s Birthday Party');
$event1->setLocation('Deathstar');
$event1->setTime(new \DateTime());
$event1->setDetails('Ha !Darth HATS surpriss !!!');
    $manager->persist($event1);

$event2=new Event();
$event2->setName('Rebellion Fundraiser Bake Sale!');
$event2->setLocation('Endor');
$event2->setTime(new \DateTime('Thursday noon'));
$event2->setDetails('Ewok pies! Support therebellion');
$manager->persist($event2);
  // the queries aren't done until now
 $manager->flush();
}
} 