<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\Events;
use App\Entity\User;
use App\Lib\CleaningCreator;
use App\Lib\EventCreator;
use App\Lib\ReservationCreator;
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

    protected function eventCreate(EventCreator $eventCreator, $data)
    {

    }

    /**
     * Create event and reservation.
     *
     * TODO Use single responsibility and move creating reservations.
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