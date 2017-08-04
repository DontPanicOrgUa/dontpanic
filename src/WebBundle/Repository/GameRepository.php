<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function getAllGamesByRoom($slug)
    {
        return $this->createQueryBuilder('g')
            ->leftJoin('g.customer', 'gc')
            ->leftJoin('g.bills', 'gb')
            ->leftJoin('gb.payments', 'gbp')
            ->innerJoin('g.room', 'gr', 'WHERE', 'gr.slug = :slug')
            ->setParameter('slug', $slug)
            ->addSelect('gc', 'gb', 'gbp', 'gr')
            ->getQuery();
    }
//    public function findByIdWithRelatedData($id)
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.id = :id')
//            ->setParameter('id', $id)
//            ->leftJoin('g.customer', 'c')
//            ->leftJoin('g.payment', 'p')
//            ->leftJoin('g.room', 'r')
//            ->addSelect('g')
//            ->addSelect('c')
//            ->addSelect('p')
//            ->addSelect('r')
//            ->getQuery()
//            ->getOneOrNullResult();
//    }
//
//    public function findAllBelongsToRoom($search = null, $dateStart = null, $dateEnd = null, Room $room)
//    {
//        $queryBuilder = $this->createQueryBuilder('g');
//
//        if ($search) {
//            $queryBuilder = $this->search($queryBuilder, $search, $room);
//            $queryBuilder = $this->dateTimeRange($queryBuilder, $dateStart, $dateEnd);
//            return $queryBuilder->getQuery();
//        }
//
//        $queryBuilder
//            ->leftJoin('g.bills', 'gb')
//            ->leftJoin('g.customer', 'gc')
//            ->leftJoin('g.room', 'gr')
//            ->leftJoin('gb.payments', 'gbp')
//            ->addSelect('g')
//            ->addSelect('gb')
//            ->addSelect('gc')
//            ->addSelect('gr')
//            ->addSelect('gbp');
//
//        $queryBuilder = $this->dateTimeRange($queryBuilder, $dateStart, $dateEnd);
//
//        return $queryBuilder->getQuery();
//    }
//
//    private function search(QueryBuilder $queryBuilder, $search, Room $room)
//    {
//        $queryBuilder
//            ->leftJoin('g.bills', 'gb')
//            ->innerJoin('g.room', 'gr')
//            ->orWhere('gr.titleEn LIKE :s')
//            ->innerJoin('g.customer', 'gc')
//            ->orWhere('gc.name LIKE :s')
//            ->orWhere('gc.lastName LIKE :s')
//            ->orWhere('gc.phone LIKE :s')
//            ->orWhere('gc.email LIKE :s')
//            ->setParameter('s', '%' . $search . '%')
//            ->addSelect('gr')
//            ->addSelect('gc')
//            ->addSelect('gb');
//
//        if (in_array('ROLE_ADMIN', $user->getRoles())) {
//            return $queryBuilder;
//        }
//
//        return $queryBuilder->innerJoin('g.room.roomManagers', 'grm')
//            ->andWhere('grm.id = :user_id')
//            ->setParameter('user_id', $user->getId());
//    }
//
//    private function dateTimeRange(QueryBuilder $queryBuilder, $dateStart = null, $dateEnd = null)
//    {
//        if ($dateStart) {
//            $queryBuilder
//                ->andWhere('g.datetime > :start')
//                ->setParameter('start', DateTime::createFromFormat('d.m.Y H:i', $dateStart), Type::DATETIME);
//        }
//        if ($dateEnd) {
//            $queryBuilder
//                ->andWhere('g.datetime < :end')
//                ->setParameter('end', DateTime::createFromFormat('d.m.Y H:i', $dateEnd), Type::DATETIME);
//        }
//        return $queryBuilder;
//    }
}