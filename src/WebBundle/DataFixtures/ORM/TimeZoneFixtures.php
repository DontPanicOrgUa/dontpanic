<?php

namespace WebBundle\DataFixtures\ORM;


use WebBundle\Entity\TimeZone;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class TimeZoneFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $zone = new TimeZone();
        $zone->setTitle('Europe/Kiev');
        $manager->persist($zone);
        $manager->flush();

        $zone2 = new TimeZone();
        $zone2->setTitle('Europe/Brussels');
        $manager->persist($zone2);
        $manager->flush();
    }
}