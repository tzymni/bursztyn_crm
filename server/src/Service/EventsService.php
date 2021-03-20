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
     * Create event and reservation.
     *
     * @param EventCreator $eventCreator
     * @param $data
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

    /**
     * @param $type
     * @param $dateFrom
     * @param $dateTo
     * @return object|string
     */
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
     * @param $type
     * @param $dateFrom
     * @return object|string
     */
    public function getAllFutureActiveEventsByType($type)
    {
        $dateFrom = gmdate("Y-m-d 00:00:00");
        $query = $this->em->createQueryBuilder()
            ->select(array('e'))
            ->from('App:Events', 'e')
            ->where('e.is_active = :isActive')
            ->andWhere('e.type = :type')
            ->andWhere('e.date_from >= :dateFrom')
            ->orderBy('e.date_from ', 'ASC')
            ->setParameter('isActive', true)
            ->setParameter('type', $type)
            ->setParameter('dateFrom', $dateFrom);

        $events = $query->getQuery()->getResult();

        if (isset($events) && isset($events[0])) {
            return $events;
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