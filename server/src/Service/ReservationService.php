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
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ReservationService
 * @package App\Service
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class ReservationService
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

        return 'Reservation for ' . $firstName . ' ' . $lastName;
    }

    /**
     * Create reservation by Events.
     *
     * @param Events $event
     * @param array $data
     */
    public function createReservation(Events $event, array $data)
    {
        $cottageService = new CottageService($this->em);
        $cottageResponse = $cottageService->getActiveCottageById($data['cottage_id']);

        if (!$cottageResponse instanceof Cottages) {
            throw new \Exception($cottageResponse);
        }

        $guestsNumber = empty($data['guests_number']) ? 0 : intval($data['guests_number']);
        $extraInfo = empty($data['extra_info']) ? null :$data['extra_info'];

        $reservation = new Reservations();
        $reservation->setCottage($cottageResponse);
        $reservation->setEvent($event);
        $reservation->setGuestFirstName($data['guest_first_name']);
        $reservation->setGuestLastName($data['guest_last_name']);
        $reservation->setGuestPhoneNumber($data['guest_phone_number']);
        $reservation->setIsActive(true);
        $reservation->setGuestsNumber($guestsNumber);
        $reservation->setAdvancePayment($data['advance_payment']);
        $reservation->setExtraInfo($extraInfo);
        $this->em->persist($reservation);

        $this->em->flush();
    }

}
