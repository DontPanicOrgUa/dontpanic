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
        $genre->setTitleRu('Приключения');
        $genre->setTitleEn('Adventure');
        $genre->setTitleDe('Abenteuer');

        $genre2 = new Genre();
        $genre2->setTitleRu('Триллер');
        $genre2->setTitleEn('Thriller');
        $genre2->setTitleDe('Thriller');

        $genre3 = new Genre();
        $genre3->setTitleRu('Книги и кино');
        $genre3->setTitleEn('Books and movies');
        $genre3->setTitleDe('Bücher und Filme');

        $manager->persist($genre);
        $manager->persist($genre2);
        $manager->persist($genre3);
        $manager->flush();
    }
}