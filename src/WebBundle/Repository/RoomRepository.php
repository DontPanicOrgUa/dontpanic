<?php

namespace WebBundle\Repository;


use DateTime;
use WebBundle\Entity\Room;
use AdminBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class RoomRepository extends EntityRepository
{
    public function queryFindAllByUserRights(User $user)
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.city', 'c')
            ->leftJoin('r.currency', 'cu')
            ->addSelect('c')
            ->addSelect('cu');

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $qb->getQuery();
        }

        $qb->innerJoin('r.roomManagers', 'rm')
            ->where('rm.id = :user_id')
            ->setParameter('user_id', $user->getId());

        return $qb->getQuery();

    }

    /**
     * @param string $slug
     * @return Room
     */
    public function findBySlug($slug)
    {
        return $this->createQueryBuilder('r')
            ->where('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->leftJoin('r.blanks', 'b')
            ->leftJoin('r.timezone', 't')
            ->leftJoin('b.prices', 'p')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('t')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getSingleResult();
    }

    public function findBySlugWithActualGames($slug)
    {
        $dateTimeFrom = new DateTime('now - 14 days');
        $dateTimeTo = new DateTime('now + 14 days');
        return $this->createQueryBuilder('r')
            ->andWhere('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->leftJoin('r.blanks', 'b')
            ->leftJoin('r.timezone', 't')
            ->leftJoin('b.prices', 'p')
            ->leftJoin('r.games', 'g', 'WITH', 'g.datetime > :dateTimeFrom AND g.datetime < :dateTimeTo')
            ->setParameter('dateTimeFrom', $dateTimeFrom)
            ->setParameter('dateTimeTo', $dateTimeTo)
            ->addSelect('g')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('t')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}