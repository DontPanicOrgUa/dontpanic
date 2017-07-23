<?php

namespace WebBundle\Repository;


use DateTime;
use AdminBundle\Entity\User;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class GameRepository extends EntityRepository
{
    public function findByIdWithRelatedData($id)
    {
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

    public function queryFindAllByUserRights($search = null, $dateStart = null, $dateEnd = null, User $user)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        if ($search) {
            $queryBuilder = $this->search($queryBuilder, $search, $user);
            $queryBuilder = $this->dateTimeRange($queryBuilder, $dateStart, $dateEnd);
            return $queryBuilder->getQuery();
        }

        $queryBuilder
            ->leftJoin('g.payment', 'gp')
            ->leftJoin('g.customer', 'gc')
            ->leftJoin('g.room', 'gr')
            ->addSelect('g')
            ->addSelect('gp')
            ->addSelect('gc')
            ->addSelect('gr');

        $queryBuilder = $this->dateTimeRange($queryBuilder, $dateStart, $dateEnd);

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $queryBuilder->getQuery();
        }

        $queryBuilder->innerJoin('g.room.roomManagers', 'grm')
            ->where('grm.id = :user_id')
            ->setParameter('user_id', $user->getId());

        return $queryBuilder->getQuery();
    }

    private function search(QueryBuilder $queryBuilder, $search, User $user)
    {
        $queryBuilder
            ->leftJoin('g.payment', 'gp')
            ->innerJoin('g.room', 'gr')
            ->orWhere('gr.titleEn LIKE :s')
            ->innerJoin('g.customer', 'gc')
            ->orWhere('gc.name LIKE :s')
            ->orWhere('gc.secondname LIKE :s')
            ->orWhere('gc.phone LIKE :s')
            ->orWhere('gc.email LIKE :s')
            ->setParameter('s', '%' . $search . '%')
            ->addSelect('gr')
            ->addSelect('gc')
            ->addSelect('gp');

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $queryBuilder;
        }

        return $queryBuilder->innerJoin('g.room.roomManagers', 'grm')
            ->andWhere('grm.id = :user_id')
            ->setParameter('user_id', $user->getId());
    }

    private function dateTimeRange(QueryBuilder $queryBuilder, $dateStart = null, $dateEnd = null)
    {
        if ($dateStart) {
            $queryBuilder
                ->andWhere('g.datetime > :start')
                ->setParameter('start', DateTime::createFromFormat('d.m.Y H:i', $dateStart), Type::DATETIME);
        }
        if ($dateEnd) {
            $queryBuilder
                ->andWhere('g.datetime < :end')
                ->setParameter('end', DateTime::createFromFormat('d.m.Y H:i', $dateEnd), Type::DATETIME);
        }
        return $queryBuilder;
    }
}