<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function findByIdWithRelatedData($id) {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :id')
            ->setParameter('id', $id)
            ->leftJoin('g.customer', 'c')
            ->leftJoin('g.payment', 'p')
            ->leftJoin('g.room', 'r')
            ->addSelect('g')
            ->addSelect('c')
            ->addSelect('p')
            ->addSelect('r')
            ->getQuery()
            ->getOneOrNullResult();
    }
}