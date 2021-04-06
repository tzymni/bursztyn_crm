<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CottagesCleaningEventsService to manage CottagesCleaningEvents records.
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottagesCleaningEventsService
{
    /**
     * EntityManager.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * EventsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create new CottagesCleaningEvents record.
     *
     * @param Events $event
     * @param Cottages $cottage
     * @return Events
     */
    public function createCottageCleaningEvent(Events $event, Cottages $cottage): Events
    {

        $cottagesCleaningEventsRepo = $this->em->getRepository(CottagesCleaningEvents::class);
        if (method_exists($cottagesCleaningEventsRepo, 'findCottageEventByRelations')) {
            $cottageEvent = $cottagesCleaningEventsRepo->findCottageEventByRelations($cottage, $event);
        }

        // create event only if there are not cottages cleaning events with the same cottage and event
        if (empty($cottageEvent)) {
            $cottageEvent = new CottagesCleaningEvents();
            $cottageEvent->setCottage($cottage);
            $cottageEvent->setEvent($event);
            $this->em->persist($cottageEvent);
            $this->em->flush();
        }

        return $event;
    }

    /**
     * Create details about cleaning event (which cottages, when next reservation, how long etc).
     *
     * @param $eventId
     * @return array
     */
    public function generateCottageCleaningEventDetails($eventId): array
    {
        $reservationService = new ReservationService($this->em);

        $event = $this->em->getRepository('App:Events')->findActiveById($eventId);
        $cottageCleaningEvents = $this->em->getRepository(CottagesCleaningEvents::class)->getCottageCleaningEventsByEvent($event);

        $details = array();
        foreach ($cottageCleaningEvents as $cottageCleaningEvent) {
            $tmp = array();
            $tmp['cottage_id'] = $cottageCleaningEvent->getCottage()->getId();
            $tmp['cottage_name'] = $cottageCleaningEvent->getCottage()->getName();
            $nextReservation = $reservationService->getNextActiveReservationInCottage($cottageCleaningEvent->getCottage(),
                $event->getDateTo());
            $periodInDays = 0;
            if (!empty($nextReservation)) {
                $nextReservationDateFrom = $nextReservation['date_from'];
                $nextReservationDateTo = $nextReservation['date_to'];
                $dateDiff = strtotime($nextReservationDateTo) - strtotime($nextReservationDateFrom);
                $periodInDays = round($dateDiff / (60 * 60 * 24));
            }
            $tmp['next_reservation_date'] = !empty($nextReservation) ? $nextReservation['date_from'] : 'Brak';
            $tmp['next_reservation_period'] = $periodInDays;
            $tmp['next_reservation_event_id'] = !empty($nextReservation) ? $nextReservation['event_id'] : null;
            $tmp['next_reservation_id'] = !empty($nextReservation) ? $nextReservation['reservation_id'] : null;

            $details[] = $tmp;
        }

        return $details;
    }

    /**
     * Get all cleaning events without grouping per cottage.
     *
     * @return object[]|null
     */
    public function getAllCottagesCleaningEvents(): ?array
    {
        $cottagesCleaningEventsRepo = $this->em->getRepository(CottagesCleaningEvents::class);
        if (method_exists($cottagesCleaningEventsRepo, 'getAllCottagesCleaningEvents')) {
            return $cottages = $cottagesCleaningEventsRepo->getAllCottagesCleaningEvents();
        }

    }
}