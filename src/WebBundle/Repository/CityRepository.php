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

    public function findOneByCityNameWithActiveRooms($name)
    {
        return $this->createQueryBuilder('c')
            ->where('c.nameEn = :name')
            ->setParameter('name', $name)
            ->leftJoin('c.rooms', 'r', 'WITH', 'r.enabled = :enabled')
            ->setParameter('enabled', true)
            ->addSelect('c')
            ->addSelect('r')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findAllWithActiveRooms()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.rooms', 'r', 'WITH', 'r.enabled = :enabled')
            ->setParameter('enabled', true)
            ->addSelect('c')
            ->addSelect('r')
            ->getQuery()
            ->execute();
    }
}