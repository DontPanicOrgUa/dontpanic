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
            ->addSelect('c');

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $qb
                ->leftJoin('r.roomManagers', 'm')
                ->addSelect('m')
                ->getQuery();
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

    public function findBySlugWithActualGamesAndCorrectives($slug)
    {
        $now = new DateTime('now');
        $dateTimeTo = new DateTime('now + 30 days');
        return $this->createQueryBuilder('r')
            ->andWhere('r.slug = :slug')
            ->leftJoin('r.blanks', 'b')
            ->leftJoin('r.timezone', 't')
            ->leftJoin('b.prices', 'p')
            ->leftJoin(
                'r.games',
                'g',
                'WITH',
                'g.datetime > :now AND g.datetime < :dateTimeTo'
            )
            ->leftJoin(
                'r.correctives',
                'c',
                'WITH',
                'c.datetime > :now')
            ->setParameter('slug', $slug)
            ->setParameter('dateTimeTo', $dateTimeTo)
            ->setParameter('now', $now)
            ->addSelect('g')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('t')
            ->addSelect('c')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}