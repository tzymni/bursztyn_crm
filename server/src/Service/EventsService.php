<?php

namespace App\Service;

use App\Entity\Events;
use App\Entity\User;
use App\Lib\EventCreator;
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
     * @param EventCreator $eventCreator
     * @param $data
     * @param null $event
     * @return Events|string
     * @throws \Exception
     */
    public function createEvent(EventCreator $eventCreator, $data)
    {
        $createdById = empty($data['user_id']) ? null : $data['user_id'];
        $data['type'] = empty($data['type']) ? null : $data['type'];
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : true;
        $data['date_from'] = !empty($data['date_from']) ? $data['date_from'] : null;
        $data['date_to'] = !empty($data['date_to']) ? $data['date_to'] : null;
        $userService = new UserService($this->em);

        $userResponse = $userService->getActiveUserById($createdById);

        if (!$userResponse instanceof User) {
            throw new \Exception($userResponse);
        }

        $data['user_id'] = $userResponse;
        $eventCreator->create($data);
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

    public function getNextEventByDateAndType($dateFrom, $type)
    {
        $event = $this->em->getRepository('App:Events')->findBy(
            array("is_active" => true, "type" => $type, "date_from >= $dateFrom"),
            array(),
            array(1)
        );

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {
            return sprintf("Can't find event!");
        }
    }

    public function getActiveEventByDateAndType($type, $dateFrom, $dateTo)
    {
        $event = $this->em->getRepository('App:Events')->findBy(
            array("is_active" => true, "type" => $type, "date_from" => $dateFrom, "date_to" => $dateTo),
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
     * Get list of all active events by type.
     *
     * @param null $type
     * @return object[]
     */
    public function getActiveEvents($type = null): array
    {
        if (empty($type) || $type == 'ALL') {
            $conditions = array("is_active" => true);

        } else {
            $conditions = array("is_active" => true, "type" => $type);
        }

        return $this->em->getRepository('App:Events')->findBy(
            $conditions,
            array()
        );
    }

}