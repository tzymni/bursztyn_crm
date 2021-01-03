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
     * @param $data
     * @return string
     */
    public function generateTitle($data): string
    {
        $firstName = $data['guest_first_name'];
        $lastName = $data['guest_last_name'];
        $dateFrom = $data['date_from'];
        $dateTo = $data['date_to'];

        return 'Reservation for ' . $firstName . ' ' . $lastName . ' (' . $dateFrom . ' - ' . $dateTo . ')';
    }

    /**
     * Create reservation by Events.
     *
     * @param Events $event
     * @param array $data
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

        $status = isset($data['status']) ? $data['status'] : 'DEFAULT';

        $dateAdd = isset($data['date_add']) ? $data['date_add'] : gmdate("Y-m-d H:i:s");
        $dateAdd =\DateTime::createFromFormat("Y-m-d H:i:s", $dateAdd);

        $reservation->setCottage($cottageResponse);
        $reservation->setEvent($event);
        $reservation->setGuestFirstName($data['guest_first_name']);
        $reservation->setGuestLastName($data['guest_last_name']);
        $reservation->setGuestPhoneNumber($data['guest_phone_number']);
        $reservation->setIsActive(true);
        $reservation->setGuestsNumber($guestsNumber);
        $reservation->setAdvancePayment($data['advance_payment']);
        $reservation->setExtraInfo($extraInfo);
        $reservation->setStatus($status);
        $reservation->setDateAdd($dateAdd);

        $this->em->persist($reservation);

        $this->em->flush();
    }


    public function getEventDetails(int $eventId)
    {
        return $this->getActiveReservationByEventId($eventId);
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
}
