<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\Reservations;
use App\Service\interfaces\DecorateEventInterface;
use Doctrine\ORM\EntityManagerInterface;

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
     * @throws \Exception
     */
    public function createReservation(Events $event, array $data, $reservation = null)
    {
        $cottageService = new CottageService($this->em);
        $cottageResponse = $cottageService->getActiveCottageById($data['cottage_id']);

        if (!$cottageResponse instanceof Cottages) {
            throw new \Exception($cottageResponse);
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

    public function getEventDetails(int $eventId)
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
     * @return bool
     * @throws \Exception
     */
    public function checkCottageAvailability($cottageId, $dateFrom, $dateTo, $eventId = null): bool
    {
        $cottageService = new CottageService($this->em);
        $cottageResponse = $cottageService->getActiveCottageById($cottageId);

        if (!$cottageResponse instanceof Cottages) {
            throw new \Exception($cottageResponse);
        }

        $query = $this->em->createQueryBuilder()
            ->select('e.id, r.id')
            ->from('App:Reservations', 'r')
            ->leftJoin('r.event', 'e')
            ->andWhere('(e.date_from_unix_utc >= :dateFrom AND e.date_from_unix_utc < :dateTo)')
            ->orWhere('(e.date_to_unix_utc <= :dateTo AND e.date_to_unix_utc > :dateFrom)')
            ->orWhere('(e.date_from_unix_utc < :dateFrom AND e.date_to_unix_utc > :dateTo)')
            ->andWhere('r.cottage=:cottageId')
            ->setParameter('cottageId', $cottageId)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo);

        if ($eventId > 0) {
            $query->andWhere('r.event != :eventId')
                ->setParameter('eventId', $eventId);
        }

        $result = $query->getQuery()->getResult();

        if (empty($result)) {
            return true;
        } else {
            $dateFrom = gmdate("Y-m-d", $dateFrom);
            $dateTo = gmdate("Y-m-d", $dateTo);
            $message = sprintf(
                "There is a reservation between %s and %s for cottage %s",
                $dateFrom,
                $dateTo,
                $cottageResponse->getName()
            );
            throw new \Exception($message);
        }
    }

    /**
     * @param $eventId
     */
    public function getActiveReservationByEventId($eventId)
    {

        $event = $this->em->getRepository('App:Reservations')->createQueryBuilder('p')
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

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {
            return sprintf("Can't find event!");
        }
    }

    /**
     * Get ID of cottage by external_id.
     *
     * @param $externalId
     * @return Cottages|null
     */
    public function getReservationByExternalId($externalId)
    {

        $reservation = null;

        $reservation = $this->em->getRepository('App:Reservations')->findBy(
            array("external_id" => $externalId),
            array(),
            array(1)
        );

        if (isset($reservation) && isset($reservation[0])) {
            return $reservation[0];
        } else {
            return null;
        }
    }
}
