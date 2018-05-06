<?php

declare(strict_types=1);

namespace RoomBundle\Repository;


use DateTime;
use AdminBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use RoomBundle\Entity\Room;

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
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findBySlug($slug)
    {
        return $this->createQueryBuilder('r')
            ->where('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->leftJoin('r.blanks', 'b')
            ->leftJoin('r.city', 'rc')
            ->leftJoin('rc.timezone', 'rct')
            ->leftJoin('b.prices', 'p')
            ->addSelect('b')
            ->addSelect('p')
//            ->addSelect('rct')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getSingleResult();
    }

    public function findBySlugForWeb($slug)
    {
        $now = new DateTime('now');
        $dateTimeTo = new DateTime('now + 30 days');
        return $this->createQueryBuilder('r')
            ->andWhere('r.slug = :slug')
            ->leftJoin('r.blanks', 'b')
            ->leftJoin('r.city', 'rc')
            ->leftJoin('rc.timezone', 'rct')
            ->leftJoin('b.prices', 'p')
            ->leftJoin('r.currency', 'cu')
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
            ->leftJoin(
                'r.feedbacks',
                'rf',
                'WITH',
                'rf.isActive = :isActive'
            )
            ->setParameter('slug', $slug)
            ->setParameter('dateTimeTo', $dateTimeTo)
            ->setParameter('now', $now)
            ->setParameter('isActive', true)
            ->addSelect('g')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('c')
            ->addSelect('cu')
            ->addSelect('rf')
            ->addSelect('rc')
            ->addSelect('rct')
//            ->addSelect('r.id')
//            ->addSelect('AVG(g.result) as g_result')
//            ->groupBy('r.id ')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllActive()
    {
        return $this->createQueryBuilder('r')
            ->where('r.enabled = :enabled')
            ->setParameter('enabled', true)
            ->addSelect('r')
            ->getQuery()
            ->getResult();
    }

    public function findAllByCity($cityName, $sort, $order)
    {
        $queryBuilder = $this
            ->createQueryBuilder('r')
            ->select('r as room');

        if ($cityName) {
            $queryBuilder
                ->innerJoin('r.city', 'rc')
                ->where('rc.nameEn = :cityName')
                ->setParameter('cityName', $cityName)
                ->addSelect('rc');
        }

        if ($sort) {
            if (!$order) {
                $order = 'DESC';
            }
            $queryBuilder
                ->leftJoin(
                    'r.feedbacks',
                    'rf',
                    'WITH',
                    'rf.isActive = :isActive'
                )
                ->setParameter('isActive', true)
                ->addSelect('AVG(rf.atmosphere) as s_atmosphere')
                ->addSelect('AVG(rf.story) as s_story')
                ->addSelect('AVG(rf.service) as s_service')
                ->addSelect('r.createdAt as s_newest')
                ->addSelect('r.difficulty as s_complexity')
                ->addSelect('r.sort')
                ->addOrderBy('r.sort', 'DESC')
                ->addOrderBy('s_' . $sort, $order)
                ->groupBy('r');
        }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}