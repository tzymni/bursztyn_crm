<?php

namespace App\Lib;

use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EventCreator
 * @package App\Lib
 */
abstract class EventCreator
{
    /**
     * EntityManager.
     *
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * EventsService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return EventParser
     */
    abstract public function getEvent(): EventParser;

    /**
     * @param $data
     * @return Events|mixed
     */
    public function create($data)
    {

        $eventId = null;

        $event = null;

        if (isset($data['event'])) {
            $event = $data['event'];
        }

        if (empty($event) || !$event instanceof Events) {
            $event = new Events();
        }

        if (!$this->isTimestamp($data['date_from'])) {
            $data['date_from'] = strtotime($data['date_from'] . ' UTC');
        }

        if (!$this->isTimestamp($data['date_to'])) {
            $data['date_to'] = strtotime($data['date_to'] . ' UTC');
        }

        $event->setCreatedBy($data['user_id']);
        $event->setType($data['type']);
        $event->setTitle($data['title']);
        $event->setDateFromUnixUtc($data['date_from']);
        $event->setDateFrom(gmdate("Y-m-d H:i", $data['date_from']));
        $event->setDateTo(gmdate("Y-m-d H:i", $data['date_to']));
        $event->setDateToUnixUtc($data['date_to']);
        $event->setIsActive($data['is_active']);
        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

    /**
     * Check if string is timestamp.
     *
     * @param $string
     * @return bool
     */
    protected function isTimestamp($string): bool
    {
        return (1 === preg_match('~^[1-9][0-9]*$~', $string));
    }
}