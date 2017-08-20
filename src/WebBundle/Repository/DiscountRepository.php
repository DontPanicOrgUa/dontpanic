<?php

namespace WebBundle\Repository;


use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

class DiscountRepository extends EntityRepository
{
    public function findOneByCode($code)
    {
        return $this->createQueryBuilder('d')
            ->where('d.code = :code')
            ->setParameter('code', $code)
            ->leftJoin('d.customer', 'dc')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}