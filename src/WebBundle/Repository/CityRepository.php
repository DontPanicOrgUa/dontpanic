<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function createAlphabeticalQueryBuilder()
    {
        return $this->createQueryBuilder('city')
            ->orderBy('city.nameEn', 'ASC');
    }
}