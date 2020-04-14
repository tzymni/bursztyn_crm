<?php

namespace App\Service;

use App\Entity\Events;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use App\EventListener\ReservationAfterEventSave;
use PHPUnit\Runner\Exception;

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
     * EntityMenager.
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
    protected function isTimestamp($string)
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
     * Create event and reservation.
     *
     * TODO Use single responsibility and move creating reservations.
     * @param $data
     * @return Events|string
     */
    public function createEvent($data)
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
                $dateFrom = strtotime($dateFrom, 'UTC');
            }

            if (!$this->isTimestamp($dateTo)) {
                $dateTo = strtotime($dateTo, 'UTC');
            }

            if (empty($dateFrom || empty($dateTo))) {
                throw new \Exception('Wrong date format!');
            }

            if (!$this->isValidType($type)) {
                throw new \Exception('Wrong type!');
            }

            $event = new Events();
            $event->setCreatedBy($userResponse);
            $event->setType($type);
            $event->setTitle($title);
            $event->setDateFromUnixUtc($dateFrom);
            $event->setDateToUnixUtc($dateTo);
            $event->setIsActive($isActive);
            $this->em->persist($event);
            $this->em->flush();

            if ($event instanceof Events) {
                $reservationService->createReservation($event, $data);
            }

            return $event;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

}