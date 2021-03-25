<?php
namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\Reservations;
use App\Repository\CottagesRepository;
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
        $cottageRepository = $this->em->getRepository(Cottages::class);
        $cottageResponse = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottageResponse = $cottageRepository->findActiveById($data['cottage_id']);
        }

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

    /**
     * @param int $eventId
     * @return string
     */
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
     * @param null $eventId
     * @return bool
     * @throws \Exception
     */
    public function checkCottageAvailability($cottageId, $dateFrom, $dateTo, $eventId = null): bool
    {
        $cottageRepository = $this->em->getRepository(Cottages::class);
        $cottageResponse = null;
        if ($cottageRepository instanceof CottagesRepository) {
            $cottageResponse = $cottageRepository->findActiveById($cottageId);
        }

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
            ->andWhere('e.is_active=:isActive')
            ->setParameter('cottageId', $cottageId)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('isActive', true)
            ->setParameter('dateTo', $dateTo);

        if ($eventId > 0) {
            $query->andWhere('r.event != :eventId')
                ->setParameter('eventId', $eventId);
        }

        $result = $query->getQuery()->getResult();

        if (empty($result)) {
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
    public function getNextActiveReservationByCottage(Cottages $cottages, $dateFrom): array
    {
        $event = $this->em->getRepository('App:Reservations')->createQueryBuilder('p')
            ->select(
                'p.id as reservation_id, p.guest_first_name, p.guest_last_name,  p.guests_number,
                  Events.id as event_id, Events.date_from, Events.date_to, Events.title  '
            )
            ->andWhere('p.is_active = :active')
            ->andWhere('p.cottage = :cottage')
            ->andWhere('Events.date_from >= :date_from')
            ->setParameter('active', true)
            ->setParameter('cottage', $cottages->getId())
            ->setParameter('date_from', $dateFrom)
            ->leftJoin('p.event', 'Events')
            ->addOrderBy('Events.date_from', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->execute();

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {

            return array();
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
