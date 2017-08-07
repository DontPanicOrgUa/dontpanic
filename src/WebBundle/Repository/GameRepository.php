<?php

namespace WebBundle\Repository;


use DateTime;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function getAllGamesByRoom($slug, $search = null, $dateStart = null, $dateEnd = null)
    {

        $builder = $this
            ->createQueryBuilder('g')
            ->leftJoin('g.bills', 'gb')
            ->leftJoin('gb.payments', 'gbp')
            ->innerJoin('g.room', 'gr', 'WITH', 'gr.slug = :slug')
            ->setParameter('slug', $slug);

        if ($search) {
            $builder
                ->orWhere('gr.titleEn LIKE :s')
                ->innerJoin('g.customer', 'gc')
                ->orWhere('gc.name LIKE :s')
                ->orWhere('gc.lastName LIKE :s')
                ->orWhere('gc.phone LIKE :s')
                ->orWhere('gc.email LIKE :s')
                ->setParameter('s', '%' . $search . '%');
        } else {
            $builder->leftJoin('g.customer', 'gc');
        }

        if ($dateStart) {
            $builder
                ->andWhere('g.datetime > :start')
                ->setParameter('start', DateTime::createFromFormat('d.m.Y H:i', $dateStart), Type::DATETIME);
        }
        if ($dateEnd) {
            $builder
                ->andWhere('g.datetime < :end')
                ->setParameter('end', DateTime::createFromFormat('d.m.Y H:i', $dateEnd), Type::DATETIME);
        }

        return $builder
            ->addSelect('gc', 'gb', 'gbp', 'gr')
            ->orderBy('g.datetime', 'DESC')
            ->getQuery();
    }
}