<?php

namespace Ferus\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function findSearch($search)
    {
        $qb = $this->createQueryBuilder('e');

        $search = trim($search);
        $words = explode(' ', $search);

        foreach ($words as $key => $word) {
            $qb
                ->andWhere(
                    $qb->expr()->like('e.name', ":word$key")
                )
                ->setParameter("word$key", "%$word%")
            ;
        }

        $qb->orderBy('e.date', 'DESC');

        return $qb->getQuery()->getResult();
    }

    public function myFind($event_id)
    {
        return $this->createQueryBuilder('e')
            ->join('e.previs', 'pre')
            ->addSelect('pre')
            ->join('pre.previs_products', 'pp')
            ->addSelect('pp')
            ->join('pp.product', 'pro')
            ->addSelect('pro')
            ->join('pro.category', 'c')
            ->addSelect('c')
            ->join('e.bars', 'b')
            ->addSelect('b')
            ->where('e.id = :event_id')
            ->setParameter('event_id', $event_id)
            ->getQuery()
            ->getResult();
    }
}
