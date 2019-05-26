<?php

namespace App\Repository;

use App\Entity\Cottages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Cottages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cottages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cottages[]    findAll()
 * @method Cottages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CottagesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cottages::class);
    }

//    /**
//     * @return Cottages[] Returns an array of Cottages objects
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
    public function findOneBySomeField($value): ?Cottages
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
