<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\Reservations;
use App\Repository\CottagesRepository;
use App\Repository\ReservationsRepository;
use App\Service\interfaces\DecorateEventInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

/**
 * Class ReservationService
 * @package App\Service
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class ReservationService implements DecorateEventInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ReservationService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Create reservation by Events.
     *
     * @param Events $event
     * @param array $data
     * @param null $reservation
     * @throws Exception
     */
    public function createReservation(Events $event, array $data, $reservation = null)
    {
        $cottageRepository = $this->em->getRepository(Cottages::class);
        $cottageResponse = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottageResponse = $cottageRepository->findActiveById($data['cottage_id']);
        }

        if (!$cottageResponse instanceof Cottages) {
            throw new Exception($cottageResponse);
        }

        $guestsNumber = empty($data['guests_number']) ? 0 : intval($data['guests_number']);
        $extraInfo = empty($data['extra_info']) ? null : $data['extra_info'];
        if (empty($reservation) || !$reservation instanceof Reservations) {
            $reservation = new Reservations();
        }

        if (!isset($data['is_active'])) {
            $isActive = true;
        } else {
            $isActive = $data['is_active'];
        }

        $status = isset($data['status']) ? $data['status'] : 'DEFAULT';

        $dateAdd = isset($data['date_add']) ? $data['date_add'] : gmdate("Y-m-d H:i:s");
        $dateAdd = \DateTime::createFromFormat("Y-m-d H:i:s", $dateAdd);

        $reservation->setCottage($cottageResponse);
        $reservation->setEvent($event);
        $reservation->setGuestFirstName($data['guest_first_name']);
        $reservation->setGuestLastName($data['guest_last_name']);
        $reservation->setGuestPhoneNumber($data['guest_phone_number']);
        $reservation->setIsActive($isActive);
        $reservation->setGuestsNumber($guestsNumber);
        $reservation->setAdvancePayment($data['advance_payment']);
        $reservation->setExtraInfo($extraInfo);
        $reservation->setStatus($status);
        $reservation->setDateAdd($dateAdd);
        if (isset($data['external_id'])) {
            $reservation->setExternalId($data['external_id']);
        }

        $this->em->persist($reservation);

        $this->em->flush();
    }

    /**
     * @param int $eventId
     * @return string
     */
    public function getEventDetails(int $eventId): string
    {
        return $this->getActiveReservationByEventId($eventId);
    }

    /**
     * Check cottage availability between two given dates.
     * Throws error if reservation for given cottage exist between given dates.
     *
     * @param $cottageId
     * @param $dateFrom
     * @param $dateTo
     * @param null $eventId
     * @return bool
     * @throws Exception
     */
    public function checkCottageAvailability($cottageId, $dateFrom, $dateTo, $eventId = null): bool
    {
        $cottageRepository = $this->em->getRepository(Cottages::class);
        $cottageResponse = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottageResponse = $cottageRepository->findActiveById($cottageId);
        }

        if (!$cottageResponse instanceof Cottages) {
            throw new Exception($cottageResponse);
        }

        $reservationRepository = $this->em->getRepository(Reservations::class);

        $reservations = array();
        if ($reservationRepository instanceof ReservationsRepository) {
            $reservations = $reservationRepository->findActiveReservationForCottageBetweenDates($cottageId, $dateFrom, $dateTo, $eventId);
        }

        if (empty($reservations)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param Cottages $cottages
     * @param $dateFrom
     * @return array
     */
    public function getNextActiveReservationInCottage(Cottages $cottages, $dateFrom): array
    {
        $reservationRepository = $this->em->getRepository(Reservations::class);

        $reservation = array();
        if ($reservationRepository instanceof ReservationsRepository) {
            $reservation = $reservationRepository->findNextReservationInCottage($cottages, $dateFrom);
        }

        if (isset($reservation) && isset($reservation[0])) {
            return $reservation[0];
        } else {
            return $reservation;
        }
    }

    /**
     * Get active reservation by event id.
     *
     * @param $eventId
     * @return array
     */
    public function getActiveReservationByEventId($eventId): array
    {

        $reservationRepository = $this->em->getRepository(Reservations::class);

        $reservations = array();
        if ($reservationRepository instanceof ReservationsRepository) {
            $reservations = $reservationRepository->findActiveReservationByEventId($eventId);
        }

        return current($reservations);
    }

    /**
     * Get ID of cottage by external_id.
     *
     * @param $externalId
     * @return object|null
     */
    public function getReservationByExternalId($externalId): ?object
    {

        $reservationRepository = $this->em->getRepository(Reservations::class);

        $reservation = array();
        if ($reservationRepository instanceof ReservationsRepository) {
            $reservation = $reservationRepository->findReservationByExternalId($externalId);
        }

        if (isset($reservation) && isset($reservation[0])) {
            return $reservation[0];
        } else {
            return null;
        }
    }
}
