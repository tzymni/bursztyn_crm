<?php

namespace App\Service;

use App\Entity\Events;
use App\Entity\Users;
use App\Lib\EventCreator;
use App\Repository\EventsRepository;
use App\Service\interfaces\DecorateEventInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\EventListener\ReservationAfterEventSave;
use Exception;

/**
 * Class EventsService
 *
 * @package App\Service
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class EventsService implements DecorateEventInterface
{

    /**
     * EntityManager.
     *
     * @var EntityManagerInterface
     */
    public $em;

    /**
     * EventsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get more information about the event.
     *
     *
     * @param int $eventId
     * @return array
     */
    public function getEventDetails(int $eventId): array
    {

        $event = $this->getActiveEventById($eventId);

        if ($event instanceof Events) {

            $data = [
                'id' => $event->getId(),
                'name' => $event->getTitle(),
                'date' => $event->getDateFrom(),
            ];
        } else {
            $data = array();
        }

        return $data;
    }

    /**
     * Create event and reservation.
     *
     * @param EventCreator $eventCreator
     * @param $data
     * @return Events|string
     * @throws Exception
     */
    public function createEvent(EventCreator $eventCreator, $data)
    {
        $createdById = empty($data['created_by_id']) ? null : $data['created_by_id'];
        $data['type'] = empty($data['type']) ? null : $data['type'];
        $data['is_active'] = isset($data['is_active']) ? $data['is_active'] : true;
        $data['date_from'] = !empty($data['date_from']) ? $data['date_from'] : null;
        $data['date_to'] = !empty($data['date_to']) ? $data['date_to'] : null;

        $userService = new UsersService($this->em);

        $userResponse = $userService->getActiveUserById($createdById);

        if (!$userResponse instanceof Users) {
            throw new Exception($userResponse);
        }

        $data['created_by_id'] = $userResponse;
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

        $repository = $this->em->getRepository('App:Events');

        $event = null;
        if ($repository instanceof EventsRepository) {
            $event = $repository->findActiveById($id);
        }

        if (empty($event)) {
            return sprintf("Can't find event!");
        } else {
            return $event;
        }
    }

    /**
     * Get all future active events.
     *
     * @param $type
     * @return object|string
     */
    public function getAllFutureActiveEventsByType($type)
    {
        $events = null;
        $repository = $this->em->getRepository('App:Events');

        if ($repository instanceof EventsRepository) {
            $events = $repository->findActiveNextEventsByType($type);
        }

        if (!empty($events)) {
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

        $events = null;
        $repository = $this->em->getRepository('App:Events');

        if ($repository instanceof EventsRepository) {
            $events = $repository->findActiveEvents($type);
        }

        return $events;
    }

    /**
     * Get list of all active events by type.
     *
     * @param null $type
     * @return object[]
     */
    public function getActiveEventsBetweenDate($type, $date):? array
    {

        $events = null;
        $repository = $this->em->getRepository('App:Events');

        if ($repository instanceof EventsRepository) {
            $events = $repository->findActiveEventsBetweenDate($type, $date);
        }

        return $events;
    }

}