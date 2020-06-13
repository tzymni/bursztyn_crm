<?php

namespace App\Lib;

use App\Entity\Events;
use App\Service\interfaces\DecorateEventInterface;

/**
 * Class EventDecorator to decorate event data with data of event type (e.g. Reservations data).
 * It will be used to get all event data in depended of type.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class EventDecorator
{
    /**
     * Events object.
     *
     * @var Events
     */
    public $event;

    /**
     * EventDecorator constructor.
     * @param Events $event
     */
    public function __construct(Events $event)
    {
        $this->event = $event;
    }

    /**
     * Prepare and return array with most important event data.
     *
     * @return array
     */
    protected function prepareEventData(): array
    {
        $eventData = array();
        $eventData['event_id'] = $this->event->getId();
        $eventData['date_from_unix_utc'] = $this->event->getDateFromUnixUtc();
        $eventData['date_from'] = substr($this->event->getDateFrom(), 0 ,10);
        $eventData['date_to_unix_utc'] = $this->event->getDateToUnixUtc();
        $eventData['date_to'] = substr($this->event->getDateTo(), 0 ,10);

        return $eventData;
    }

    /**
     * Decorate event data with data of event type.
     *
     * @param DecorateEventInterface $assignedEvent
     * @return array
     */
    public function decorateEvent(DecorateEventInterface $assignedEvent): array
    {
        $eventData = array();
        $eventData['details'] = $assignedEvent->getEventDetails($this->event->getId());
        $eventData['event'] = $this->prepareEventData();
        return $eventData;
    }

}