<?php

namespace App\Repository;

use App\Entity\Reservations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservations[]    findAll()
 * @method Reservations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationsRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    /**
     *
     * @param int $eventId
     * @return int|mixed|string
     */
    public function findActiveReservationByEventId(int $eventId)
    {
        return $this->createQueryBuilder('p')
            ->select(
                'p.id as reservation_id, p.guest_first_name, p.guest_last_name, Cottages.name, Cottages.color '
            )
            ->andWhere('p.is_active = :active')
            ->andWhere('p.event = :eventId')
            ->setParameter('active', true)
            ->setParameter('eventId', $eventId)
            ->leftJoin('p.cottage', 'Cottages')
            ->getQuery()->execute();
    }

}
