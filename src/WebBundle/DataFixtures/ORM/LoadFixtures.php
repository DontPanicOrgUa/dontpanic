<?php

namespace WebBundle\DataFixtures\ORM;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__ . '/fixtures.yml',
            $manager,
            [ 'providers' => [$this] ]
        );
    }
}