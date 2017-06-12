<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PriceRepository extends EntityRepository
{
    public function getPricesByDayOfWeek()
    {
        return true;
    }
}