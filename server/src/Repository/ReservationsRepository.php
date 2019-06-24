<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, Reservations::class);
    }

    public function findActiveEvents() {
        

        $qb = $this->createQueryBuilder('p')
                ->select('p.id as reservation_id, p.date_from, p.date_to, Cottages.name, Cottages.color, p.tourist_name, p.tourist_num ')
                ->andWhere('p.is_active = :active')
                ->setParameter('active', 1)
                ->leftJoin('p.cottage', 'Cottages')
                ->getQuery()->execute();

        return $qb;
    }

//    /**
//     * @return Reservations[] Returns an array of Reservations objects
//     */
    /*
      public function findByExampleField($value)
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->orderBy('r.id', 'ASC')
      ->setMaxResults(10)
      ->getQuery()
      ->getResult()
      ;
      }
     */

    /*
      public function findOneBySomeField($value): ?Reservations
      {
      return $this->createQueryBuilder('r')
      ->andWhere('r.exampleField = :val')
      ->setParameter('val', $value)
      ->getQuery()
      ->getOneOrNullResult()
      ;
      }
     */
}
