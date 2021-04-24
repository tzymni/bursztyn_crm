<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Reservations;
use App\Entity\Users;
use App\Repository\CottagesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class to parse data from idosell system to current system.
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 **/
class ReservationsFromApiParserService
{

    /**
     * Parse data from API to format used in the system to properly save reservation in the database.
     *
     * @param EntityManagerInterface $em
     * @param array $item
     * @param array $reservation
     * @param array|null $client
     * @return array|null
     */
    public function parseApiDataToSystemFormat(
        EntityManagerInterface $em,
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

        $cottageRepository = $em->getRepository(Cottages::class);
        $cottage = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottage = $cottageRepository->findByExternalId($item['objectItemId']);
        }

        $userRepository = $em->getRepository(Users::class);
        $users = null;
        if ($userRepository instanceof UsersRepository) {
            $users = $userRepository->findAllActiveUsers();
            $user = current($users);
        } else {
            $user = array();
        }

        if (empty($cottage)) {
            return null;
        }
        $cottageId = $cottage->getId();
        $reservationEvent['cottage_id'] = $cottageId;
        $reservationEvent['cottage'] = $cottage;
        $reservationEvent['user_id'] = $user['id'];
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
        $reservationEvent['type'] = Reservations::EVENT_TYPE;
        $reservationEvent['extra_info'] = $reservationDetails['internalNote'];

        if ($reservationEvent['status'] == 'canceled') {
            $reservationEvent['is_active'] = false;
        } else {
            $reservationEvent['is_active'] = true;
        }

        return $reservationEvent;
    }

}
