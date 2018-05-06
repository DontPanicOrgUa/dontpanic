<?php

declare(strict_types=1);

namespace RoomBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CorrectiveRepository extends EntityRepository
{
    public function getCorrectiveByRoomIdAndDateTime($roomId, $dateTime)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin(
                'c.room',
                'r',
                'WITH',
                'r.id = :roomId')
            ->setParameter('roomId', $roomId)
            ->andWhere('c.datetime = :dateTime')
            ->setParameter('dateTime', $dateTime)
            ->getQuery()
            ->getOneOrNullResult();
    }
}