<?php

namespace App\Service;

use App\Entity\Cottages;
use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use Doctrine\ORM\EntityManagerInterface;

class CottagesCleaningEventsService
{
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
     * @param Events $event
     * @return array|null
     */
    public function getCottageCleaningEventsByEvent(Events $event): ?array
    {
        $cottageEvent = null;

        $cottageEvents = $this->em->getRepository('App:CottagesCleaningEvents')->findBy(
            array("event" => $event),
            array('cottage' => 'ASC')
        );

        if (isset($cottageEvents) && isset($cottageEvents[0])) {
            return $cottageEvents;
        } else {
            return null;
        }
    }

    /**
     * @param Cottages $cottage
     * @param Events $event
     * @return object|null
     */
    public function findCottageEventByRelations(Cottages $cottage, Events $event)
    {
        $cottageEvent = null;

        $cottageEvent = $this->em->getRepository('App:CottagesCleaningEvents')->findBy(
            array("cottage" => $cottage, "event" => $event),
            array(),
            array(1)
        );

        if (isset($cottageEvent) && isset($cottageEvent[0])) {
            return $cottageEvent[0];
        } else {
            return null;
        }
    }

    /**
     * @param Events $event
     * @return mixed|null
     */
    public function countCottagesByEvent(Events $event)
    {
        $cottageEvent = null;

        $eventId = $event->getId();
        $cottageEvent = $this->em->getRepository('App:CottagesCleaningEvents')->createQueryBuilder('p')
            ->select(
                'COUNT(p.id) as events_number'
            )
            ->andWhere('p.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->getQuery()->execute();

        if (isset($cottageEvent) && isset($cottageEvent[0])) {

            return $cottageEvent[0]['events_number'];
        } else {
            return null;
        }

    }

    /**
     * @param Events $event
     * @param Cottages $cottage
     */
    public function createCottageEventRecord(Events $event, Cottages $cottage)
    {

        $cottageEvent = $this->findCottageEventByRelations($cottage, $event);

        if (empty($cottageEvent)) {

            $cottageEvent = new CottagesCleaningEvents();
            $cottageEvent->setCottage($cottage);
            $cottageEvent->setEvent($event);
            $this->em->persist($cottageEvent);
            $this->em->flush();

        }
    }
}