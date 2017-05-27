<?php

namespace WebBundle\DataFixtures\ORM;


use WebBundle\Entity\City;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use WebBundle\Entity\Currency;

class CurrencyFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $currency = new Currency();
        $currency->setCurrency('UAH');

        $currency2 = new Currency();
        $currency2->setCurrency('EUR');

        $currency3 = new Currency();
        $currency3->setCurrency('USD');

        $manager->persist($currency);
        $manager->persist($currency2);
        $manager->persist($currency3);
        $manager->flush();
    }
}