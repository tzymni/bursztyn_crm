<?php

namespace App\Repository;

use App\Entity\Cottages;
use App\Entity\Events;
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

    /**
     * ReservationsRepository constructor.
     * @param ManagerRegistry $registry
     */
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
                'p.id as reservation_id, p.guest_first_name, p.guest_last_name, p.guest_phone_number, p.guests_number,
                 p.advance_payment, p.extra_info, Cottages.id as cottage_id  '
            )
            ->andWhere('p.is_active = :active')
            ->andWhere('p.event = :eventId')
            ->setParameter('active', true)
            ->setParameter('eventId', $eventId)
            ->leftJoin('p.cottage', 'Cottages')
            ->getQuery()->execute();
    }

    /**
     * Find reservation by external id.
     *
     * @param int $externalId
     * @return Reservations[]
     */
    public function findReservationByExternalId(int $externalId): array
    {
        return $this->findBy(
            array("external_id" => $externalId),
            array(),
            array(1)
        );
    }

    /**
     * @param Cottages $cottages
     * @param string $dateFrom
     * @return array
     */
    public function findNextReservationInCottage(Cottages $cottages, string $dateFrom): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                'p.id as reservation_id, p.guest_first_name, p.guest_last_name,  p.guests_number,
                  Events.id as event_id, Events.date_from, Events.date_to, Events.title  '
            )
            ->andWhere('p.is_active = :active')
            ->andWhere('p.cottage = :cottage')
            ->andWhere('Events.date_from >= :date_from')
            ->setParameter('active', true)
            ->setParameter('cottage', $cottages->getId())
            ->setParameter('date_from', $dateFrom)
            ->leftJoin('p.event', 'Events')
            ->addOrderBy('Events.date_from', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->execute();

    }

    /**
     * @param $cottageId
     * @param $dateFrom
     * @param $dateTo
     * @param null $eventId
     * @return int|mixed|string
     */
    public function findActiveReservationForCottageBetweenDates($cottageId, $dateFrom, $dateTo, $eventId = null)
    {
        $query = $this->createQueryBuilder('r')
            ->select('e.id, r.id')
            ->leftJoin('r.event', 'e')
            ->andWhere('(e.date_from_unix_utc >= :dateFrom AND e.date_from_unix_utc < :dateTo)')
            ->orWhere('(e.date_to_unix_utc <= :dateTo AND e.date_to_unix_utc > :dateFrom)')
            ->orWhere('(e.date_from_unix_utc < :dateFrom AND e.date_to_unix_utc > :dateTo)')
            ->andWhere('r.cottage=:cottageId')
            ->andWhere('e.is_active=:isActive')
            ->setParameter('cottageId', $cottageId)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('isActive', true)
            ->setParameter('dateTo', $dateTo);

        if ($eventId > 0) {
            $query->andWhere('r.event != :eventId')
                ->setParameter('eventId', $eventId);
        }

        return $query->getQuery()->getResult();
    }

}
