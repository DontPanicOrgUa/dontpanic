<?php

namespace WebBundle\DataFixtures\ORM;


use WebBundle\Entity\Genre;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

class GenreFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $genre = new Genre();
        $genre->setNameRu('Приключения');
        $genre->setNameEn('Adventure');
        $genre->setNameDe('Abenteuer');

        $genre2 = new Genre();
        $genre2->setNameRu('Триллер');
        $genre2->setNameEn('Thriller');
        $genre2->setNameDe('Thriller');

        $genre3 = new Genre();
        $genre3->setNameRu('Книги и кино');
        $genre3->setNameEn('Books and movies');
        $genre3->setNameDe('Bücher und Filme');

        $manager->persist($genre);
        $manager->persist($genre2);
        $manager->persist($genre3);
        $manager->flush();
    }
}