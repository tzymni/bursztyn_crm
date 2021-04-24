<?php

namespace App\Lib;

use App\Service\CottagesCleaningEventsService;

/**
 * Class CleaningCreator
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CleaningCreator extends EventCreator
{

    /**
     * Create cleaning event.
     *
     * @param $data
     * @return mixed|void
     */
    public function create($data)
    {
        $data = $this->getEvent()->parseData($data);
        $createdEvent = parent::create($data);
        $cottagesCleaningEventsService = new CottagesCleaningEventsService($this->em);
        $cottagesCleaningEventsService->createCottageCleaningEvent($createdEvent, $data['cottage']);
    }

    /**
     * Get event.
     *
     * @return EventParser
     */
    public function getEvent(): EventParser
    {
        return new CleaningParser($this->em);
    }

}