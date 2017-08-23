<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class RewardRepository extends EntityRepository
{
    public function findAllWithGameAndCustomer()
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.currency', 'rcurr')
            ->leftJoin('r.customer', 'rcust')
            ->leftJoin('r.game', 'rg')
            ->addSelect('rcurr', 'rcust', 'rg')
            ->getQuery();
    }
}