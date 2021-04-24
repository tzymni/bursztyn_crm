<?php

namespace App\Lib;

use App\Entity\CottagesCleaningEvents;
use App\Entity\Events;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Parse data for cleaning event.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CleaningParser implements EventParser
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ReservationParser constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Parse data.
     *
     * @param $data
     * @return array
     */
    public function parseData($data): array
    {
        $data['type'] = CottagesCleaningEvents::EVENT_TYPE;
        $data['is_active'] = true;
        $data['date_from'] = $data['date_to'];
        $data['date_to'] = $data['date_from'];

        $repository = $this->em->getRepository('App:Events');

        $event = null;
        if ($repository instanceof EventsRepository) {
            $event = $repository->findActiveEventByDateAndType($data['type'], $data['date_from'], $data['date_to']);
        }

        if ($event instanceof Events) {
            $data['event'] = $event;
        }

        $numberOfCottages = $this->getNumberOfCottagesInEvent($event, $data);
        $data['title'] = $this->generateTitle($numberOfCottages);

        return $data;
    }

    /**
     * Generate title for event.
     *
     * @param $numberOfCottages
     * @return string
     */
    private function generateTitle($numberOfCottages): string
    {
        if ($numberOfCottages == 1) {
            $changeString = 'zmiana';
        } elseif ($numberOfCottages > 1 && $numberOfCottages <= 4) {
            $changeString = 'zmiany';
        } else {
            $changeString = 'zmian';
        }

        return sprintf('Sprzątanie domków (%s %s)', $numberOfCottages, $changeString);
    }

    /**
     * Get number of cottages to cleaning in event.
     *
     * @param Events|string $event
     * @param $data
     * @return int|mixed|null
     */
    private function getNumberOfCottagesInEvent($event, $data): ?int
    {
        $cottageWillBeAdded = true;

        $numberOfCottages = null;
        if ($event instanceof Events) {
            $numberOfCottages = $this->em->getRepository(CottagesCleaningEvents::class)->countCottagesAssignedToEvent($event);
            $cottageEvent = $this->em->getRepository(CottagesCleaningEvents::class)->findCottageEventByRelations($data['cottage'],
                $event);
            if ($cottageEvent) {
                $cottageWillBeAdded = false;
            }
        }

        if ($cottageWillBeAdded) {
            $numberOfCottages += 1;
        }

        return $numberOfCottages;
    }

}