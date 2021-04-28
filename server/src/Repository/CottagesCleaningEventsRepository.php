<?php

namespace App\Repository;

use App\Entity\Cottages;
use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    /**
     * Get all CottagesCleaningEvents with defined Event.
     *
     * @param Events $event
     * @return array
     */
    public function getCottageCleaningEventsByEvent(Events $event): array
    {
        $cottageEvents = $this->findBy(
            array("event" => $event),
            array('cottage' => 'ASC')
        );

        if (isset($cottageEvents) && isset($cottageEvents[0])) {
            return $cottageEvents;
        } else {
            return array();
        }
    }

    /**
     * Get all cleaning events without grouping per cottage.
     *
     * @return object[]|null
     */
    public function getAllCottagesCleaningEvents(): ?array
    {

        $cottageEvent = null;

        $cottageEvents = $this->findBy(
            array(),
            array('cottage' => 'ASC')
        );

        if (isset($cottageEvents) && isset($cottageEvents[0])) {
            return $cottageEvents;
        } else {
            return null;
        }
    }

    /**
     * Find CottagesCleaningEvents by Cottages and Events.
     *
     * @param Cottages $cottage
     * @param Events $event
     * @return object|null
     */
    public function findCottageEventByRelations(Cottages $cottage, Events $event): ?object
    {
        $cottageEvent = null;

        $cottageEvent = $this->findBy(
            array("cottage" => $cottage, "event" => $event),
            array(),
            array(1)
        );

        if (isset($cottageEvent) && isset($cottageEvent[0])) {
            return $cottageEvent[0];
        } else {
            return null;
        }
    }

    /**
     * Return number of cottages assigned to defined Event.
     *
     * @param Events $event
     * @return mixed|null
     */
    public function countCottagesAssignedToEvent(Events $event)
    {
        $cottageEvent = null;

        $eventId = $event->getId();
        $cottageEvent = $this->createQueryBuilder('p')
            ->select(
                'COUNT(p.id) as events_number'
            )
            ->andWhere('p.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()->execute();

        if (isset($cottageEvent) && isset($cottageEvent[0])) {

            return $cottageEvent[0]['events_number'];
        } else {
            return $cottageEvent;
        }

    }
}
