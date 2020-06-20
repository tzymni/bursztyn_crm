<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\EventListener\ReservationAfterEventSave;

/**
 * Class EventsService
 * @package App\Service
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class EventsService
{

    const RESERVATION_EVENT = 'reservation';
    const CLEANING_EVENT = 'cleaning';

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
     * Check if string is timestamp.
     *
     * @param $string
     * @return bool
     */
    protected function isTimestamp($string) : bool
    {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }

    /**
     * Check if type is valid.
     *
     * @param string $type
     * @return bool
     */
    protected function isValidType(string $type): bool
    {
        if (in_array($type, array(self::RESERVATION_EVENT, self::CLEANING_EVENT))) {
            return true;
        }

        return false;
    }

    /**
     * Check cottage availability between two given dates.
     * Throws error if reservation for given cottage exist between given dates.
     * TODO Move to reservation service?
     *
     * @param $cottageId
     * @param $dateFrom
     * @param $dateTo
     * @return bool
     * @throws \Exception
     */
    public function checkCottageAvailability($cottageId, $dateFrom, $dateTo, $eventId = null)
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
     * Create event and reservation.
     *
     * TODO Use single responsibility and move creating reservations.
     * @param $data
     * @return Events|string
     */
    public function createEvent($data, $event = null)
    {
        $createdById = empty($data['user_id']) ? null : $data['user_id'];
        $type = empty($data['type']) ? null : $data['type'];
        $isActive = isset($data['is_active']) ? $data['is_active'] : true;
        $dateFrom = !empty($data['date_from']) ? $data['date_from'] : null;
        $dateTo = !empty($data['date_to']) ? $data['date_to'] : null;

        $reservationService = new ReservationService($this->em);
        $userService = new UserService($this->em);

        try {
            $userResponse = $userService->getActiveUserById($createdById);
            $title = $reservationService->generateTitle($data);

            if (!$userResponse instanceof User) {
                throw new \Exception($userResponse);
            }

            if (!$this->isTimestamp($dateFrom)) {
                $dateFrom = strtotime($dateFrom . ' UTC');
            }

            if (!$this->isTimestamp($dateTo)) {
                $dateTo = strtotime($dateTo . ' UTC');
            }

            if (empty($dateFrom) || empty($dateTo)) {
                throw new \Exception('Wrong date format!');
            }

            if (!$this->isValidType($type)) {
                throw new \Exception('Wrong type!');
            }

            $dateFrom += 12 * 3600;
            $dateTo += 9 * 3600;

            $eventId = null;

            if ($event instanceof Events) {
                $eventId = $event->getId();
            }

            $this->checkCottageAvailability($data['cottage_id'], $dateFrom, $dateTo, $eventId);
            if (empty($event) || !$event instanceof Events) {
                $event = new Events();
            }

            $event->setCreatedBy($userResponse);
            $event->setType($type);
            $event->setTitle($title);
            $event->setDateFromUnixUtc($dateFrom);
            $event->setDateFrom(gmdate("Y-m-d h:i", $dateFrom));
            $event->setDateTo(gmdate("Y-m-d h:i", $dateTo));
            $event->setDateToUnixUtc($dateTo);
            $event->setIsActive($isActive);
            $this->em->persist($event);
            $this->em->flush();

            if ($event instanceof Events) {
                if (!empty($event->getReservations())) {
                    $reservation = $event->getReservations();
                } else {
                    $reservation = null;
                }
                $reservationService->createReservation($event, $data, $reservation);
            }

            return $event;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Find active event by id.
     *
     * @param $id
     * @return object|string
     */
    public function getActiveEventById($id)
    {
        $event = $this->em->getRepository('App:Events')->findBy(
            array("is_active" => true, "id" => $id),
            array(),
            array(1)
        );

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {
            return sprintf("Can't find event!");
        }
    }

    /**
     * Get list of all active events.
     *
     * @return object[]
     */
    public function getActiveEvents()
    {
        return $this->em->getRepository('App:Events')->findBy(
            array("is_active" => true),
            array()
        );
    }

}