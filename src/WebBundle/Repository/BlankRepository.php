<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class BlankRepository extends EntityRepository
{
    public function createTimeOrderedQueryBuilder()
    {
        return $this->createQueryBuilder('blank')
            ->orderBy('blank.time', 'ASC');
    }
}