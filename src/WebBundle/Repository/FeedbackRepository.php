<?php

namespace WebBundle\Repository;


use Doctrine\ORM\EntityRepository;

class FeedbackRepository extends EntityRepository
{
    public function findAllFeedbacksByRoom($slug)
    {
        return $this->createQueryBuilder('f')
            ->innerJoin('f.room', 'fr', 'WHERE', 'fr.slug = :slug')
            ->setParameter('slug', $slug)
            ->addSelect('fr')
            ->getQuery();
    }
}