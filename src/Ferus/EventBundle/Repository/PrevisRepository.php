<?php

namespace Ferus\EventBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PrevisRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PrevisRepository extends EntityRepository
{
    public function findSearch($search)
    {
        $qb = $this->createQueryBuilder('p');

        $search = trim($search);
        $words = explode(' ', $search);

        foreach ($words as $key => $word) {
            $qb
                ->andWhere(
                    $qb->expr()->like('p.name', ":word$key")
                )
                ->setParameter("word$key", "%$word%")
            ;
        }

        return $qb->getQuery()->getResult();
    }
}
