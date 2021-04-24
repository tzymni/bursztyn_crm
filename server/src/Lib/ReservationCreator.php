<?php

namespace App\Lib;

use App\Entity\Events;
use App\Entity\Reservations;
use App\Service\ReservationService;
use Exception;

/**
 * Class to create Reservation event.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class ReservationCreator extends EventCreator
{

    /**
     * @var ReservationService
     */
    protected $reservationService;

    /**
     * @param $data
     * @throws Exception
     */
    public function create($data)
    {
        $this->reservationService = new ReservationService($this->em);
        $data = $this->getEvent()->parseData($data);

        if ($this->validateData($data)) {

            $createdEvent = parent::create($data);

            if (isset($data['reservation']) && $data['reservation'] instanceof Reservations) {
                $reservation = $data['reservation'];
            } else {
                $reservation = null;
            }

            $this->reservationService->createReservation($createdEvent, $data, $reservation);

        }
    }

    /**
     * Valid reservation data.
     *
     * @param $data
     * @return bool
     * @throws Exception
     */
    private function validateData($data): bool
    {

        $cottageId = $data['cottage_id'];
        $reservationService = new ReservationService($this->em);
        $eventId = null;
        if (isset($data['event']) && $data['event'] instanceof Events) {
            $event = $data['event'];
            $eventId = $event->getId();
        }
        return $reservationService->checkCottageAvailability($cottageId, $data['date_from'], $data['date_to'],
            $eventId);

    }

    /**
     *
     * @return EventParser
     */
    public function getEvent(): EventParser
    {
        return new ReservationParser($this->em);
    }

}