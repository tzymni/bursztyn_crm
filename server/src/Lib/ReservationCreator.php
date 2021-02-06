<?php

namespace App\Lib;

use App\Entity\Events;
use App\Service\ReservationService;

/**
 * Class ReservationCreator
 * @package App\Lib
 */
class ReservationCreator extends EventCreator
{

    /**
     * @var ReservationService
     */
    protected $reservationService;

    /**
     * @param $data
     * @param null $event
     * @return Events|mixed|void
     * @throws \Exception
     */
    public function create($data, $event = null)
    {
        $this->reservationService = new ReservationService($this->em);
        $data = $this->getEvent()->parseData($data);
        $this->validateData($data);

        $createdEvent = parent::create($data, $event);

        if (!empty($createdEvent->getReservations())) {
            $reservation = $createdEvent->getReservations();
        } else {
            $reservation = null;
        }
        $this->reservationService->createReservation($createdEvent, $data, $reservation);
    }

    /**
     * @param $data
     * @throws \Exception
     */
    private function validateData($data)
    {

        $cottageId = $data['cottage_id'];
        $reservationService = new ReservationService($this->em);
        $eventId = null;
        if (isset($data['event']) && $data['event'] instanceof Events) {
            $event = $data['event'];
            $eventId = $event->getId();
        }
        $reservationService->checkCottageAvailability($cottageId, $data['date_from'], $data['date_to'], $eventId);

    }

    /**
     * @return EventParser
     */
    public function getEvent(): EventParser
    {
        return new ReservationParser($this->em);
    }

}