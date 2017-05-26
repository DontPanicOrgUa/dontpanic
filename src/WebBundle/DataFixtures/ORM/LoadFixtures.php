<?php
/**
 * Created by PhpStorm.
 * User: mykyta
 * Date: 5/26/17
 * Time: 12:10 PM
 */

namespace WebBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use WebBundle\Entity\City;

class LoadFixtures implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $city = new City();
        $city->translate('ru')->setName('Киев');
        $city->translate('en')->setName('Kiev');
        $city->translate('de')->setName('Kiew');
        $manager->persist($city);
        // In order to persist new translations, call mergeNewTranslations method, before flush
        $city->mergeNewTranslations();
        $manager->flush();
    }
}