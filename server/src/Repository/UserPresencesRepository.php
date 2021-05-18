<?php

namespace App\Repository;

use App\Entity\UserPresences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPresences|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPresences|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPresences[]    findAll()
 * @method UserPresences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPresencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPresences::class);
    }

    // /**
    //  * @return UserPresences[] Returns an array of UserPresences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserPresences
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
