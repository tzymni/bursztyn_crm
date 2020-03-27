<?php

namespace App\Repository;

use App\Entity\CampingHouse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CampingHouse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CampingHouse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CampingHouse[]    findAll()
 * @method CampingHouse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampingHouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampingHouse::class);
    }

//    /**
//     * @return CampingHouse[] Returns an array of CampingHouse objects
//     */
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
    public function findOneBySomeField($value): ?CampingHouse
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
