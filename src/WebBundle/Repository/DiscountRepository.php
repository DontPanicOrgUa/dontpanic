<?php

namespace WebBundle\Repository;


use Doctrine\ORM\Query;
use Doctrine\ORM\EntityRepository;

class DiscountRepository extends EntityRepository
{
    public function findOneByCodeArray($code)
    {
        return $this->createQueryBuilder('d')
            ->where('d.code = :code')
            ->setParameter('code', $code)
            ->leftJoin('d.customer', 'dc')
            ->addSelect('dc')
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }

    public function findOneByCodeObject($code)
    {
        return $this->createQueryBuilder('d')
            ->where('d.code = :code')
            ->setParameter('code', $code)
            ->leftJoin('d.customer', 'dc')
            ->addSelect('dc')
            ->getQuery()
            ->getOneOrNullResult();
    }
}