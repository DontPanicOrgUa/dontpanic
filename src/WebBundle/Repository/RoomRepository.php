<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;
use WebBundle\Entity\Room;

class RoomRepository extends EntityRepository
{
    public function queryFindAll()
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.city', 'c')
            ->leftJoin('r.currency', 'cu')
            ->addSelect('c')
            ->addSelect('cu');
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
            ->leftJoin('r.timezone','t')
            ->leftJoin('b.prices','p')
            ->addSelect('b')
            ->addSelect('p')
            ->addSelect('t')
            ->orderBy('b.time', 'ASC')
            ->getQuery()
            ->getSingleResult();
    }
}