<?php

namespace WebBundle\DataFixtures\ORM;


use WebBundle\Entity\City;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $city = new City();
        $city->setNameRu('Киев');
        $city->setNameEn('Kiev');
        $city->setNameDe('Kiew');
        $manager->persist($city);
        $manager->flush();
    }
}