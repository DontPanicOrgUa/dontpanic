<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RoomRepository extends EntityRepository
{
    public function queryFindAll()
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.city', 'c')
            ->leftJoin('r.currency', 'cu')
            ->addSelect('c')
            ->addSelect('cu');
        return $qb->getQuery();
    }
}