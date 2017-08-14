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
            ->leftJoin('r.city', 'rc')
            ->leftJoin('rc.timezone', 'rct')
            ->leftJoin('b.prices', 'p')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('rct')
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

    public function findAllByCity($cityName)
    {
        $queryBuilder = $this->createQueryBuilder('r');
        if (!$cityName) {
            return $queryBuilder->where('r.enabled = :enabled')
                ->setParameter('enabled', true)
                ->addSelect('r')
                ->getQuery()
                ->getResult();
        }
        return $this->createQueryBuilder('r')
            ->innerJoin('r.city', 'rc')
            ->where('rc.nameEn = :cityName')
            ->setParameter('cityName', $cityName)
            ->addSelect('rc')
            ->getQuery()
            ->getResult();
    }
}