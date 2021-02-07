<?php

namespace App\Service;

use App\Lib\IdosellReservations;

/**
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 **/
class ReservationsFromApiParserService
{

    /**
     * Parse data from API to format used in the system to proparly save reservation in the database.
     *
     * @param CottageService $cottageService
     * @param array $item
     * @param array|null $client
     * @param array $reservation
     * @return array|null
     */
    public function parseApiDataToSystemFormat(
        CottageService $cottageService,
        array $item,
        array $reservation,
        array $client = null
    ): ?array {
        $reservationEvent = array();

        if (!isset($reservation['id'])) {
            return null;

        }
        $externalId = $reservation['id'];
        $reservationDetails = $reservation['reservationDetails'];
        $dateFrom = $reservationDetails['dateFrom'];
        $dateTo = $reservationDetails['dateTo'];

        $reservationEvent['external_id'] = $externalId;
        $reservationEvent['date_from'] = $dateFrom;
        $reservationEvent['date_to'] = $dateTo;

        $cottage = $cottageService->getCottageByExternalId($item['objectItemId']);

        if (empty($cottage)) {
            return null;
        }
        $cottageId = $cottage->getId();
        $reservationEvent['cottage_id'] = $cottageId;
        $reservationEvent['cottage'] = $cottage;
        $reservationEvent['user_id'] = 1;
        $reservationEvent['guest_first_name'] = isset($client['firstName']) ? $client['firstName'] : '-';
        $reservationEvent['guest_last_name'] = isset($client['lastName']) ? $client['lastName'] : '-';
        $reservationEvent['guest_phone_number'] = isset($client['phone']) ? $client['phone'] : '-';

        $guestsNumber = 0;
        if (isset($item['numberOfAdults']) || isset($item['numberOfSmallChildren'])) {

            $numberOfAdults = isset($item['numberOfAdults']) ? $item['numberOfAdults'] : 0;
            $numberOfSmallChildren = isset($item['numberOfSmallChildren']) ? $item['numberOfSmallChildren'] : 0;
            $guestsNumber = $numberOfAdults + $numberOfSmallChildren;
        }
        $reservationEvent['guests_number'] = $guestsNumber;
        $reservationEvent['advance_payment'] = true;
        $reservationEvent['status'] = $reservationDetails['status'];
        $reservationEvent['date_add'] = $reservationDetails['dateAdd'];
        $reservationEvent['type'] = EventsService::RESERVATION_EVENT;
        $reservationEvent['extra_info'] = $reservationDetails['internalNote'];

        if ($reservationEvent['status'] == IdosellReservations::CANCELED) {
            $reservationEvent['is_active'] = false;
        }

        return $reservationEvent;
    }

}
