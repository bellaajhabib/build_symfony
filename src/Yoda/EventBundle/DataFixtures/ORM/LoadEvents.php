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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
class LoadEvents implements FixtureInterface,OrderedFixtureInterface {
public function load(ObjectManager $manager){
    $wayne = $manager->getRepository('YodaUserBundle:User')
        ->findOneByUsernameOrEmail('wayne');
    $habib = $manager->getRepository('YodaUserBundle:User')
        ->findOneByUsernameOrEmail('habib');

    $habib1= $manager->getRepository('YodaUserBundle:User')
        ->findOneByUsernameOrEmail('habib');
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
    $event3=new Event();
    $event3->setName('Habib Sale!');
    $event3->setLocation('Tunis');
    $event3->setTime(new \DateTime('Thursday noon'));
    $event3->setDetails('Habib bellaaj in France');

$manager->persist($event3);
    $event4=new Event();
    $event4->setName('Habib Sale!');
    $event4->setLocation('Tunis');
    $event4->setTime(new \DateTime('Thursday noon'));
    $event4->setDetails('Habib bellaaj in France');

    $manager->persist($event4);
    $event1->setOwner($wayne);
    $event2->setOwner($wayne);
    $event3->setOwner($habib);
    $event4->setOwner($habib1);
  // the queries aren't done until now
 $manager->flush();
}

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return 20;
    }
}