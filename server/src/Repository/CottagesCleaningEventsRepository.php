<?php

namespace App\Repository;

use App\Entity\CottagesCleaningEvents;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CottagesCleaningEvents|null find($id, $lockMode = null, $lockVersion = null)
 * @method CottagesCleaningEvents|null findOneBy(array $criteria, array $orderBy = null)
 * @method CottagesCleaningEvents[]    findAll()
 * @method CottagesCleaningEvents[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CottagesCleaningEventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CottagesCleaningEvents::class);
    }

    // /**
    //  * @return CottagesCleaningEvents[] Returns an array of CottagesCleaningEvents objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CottagesCleaningEvents
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}