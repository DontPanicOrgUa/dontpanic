<?php

declare(strict_types=1);

namespace RoomBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function createAlphabeticalQueryBuilder()
    {
        return $this->createQueryBuilder('city')
            ->orderBy('city.nameEn', 'ASC');
    }

    public function findAllWithActiveRooms()
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.rooms', 'r', 'WITH', 'r.enabled = :enabled')
            ->setParameter('enabled', true)
            ->addSelect('c')
            ->addSelect('r')
            ->getQuery()
            ->execute();
    }
}