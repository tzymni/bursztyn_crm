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
     * @param $data
     * @return mixed|void
     */
    public function create($data)
    {

        $data = $this->getEvent()->parseData($data);
        $createdEvent = parent::create($data);
        $cottagesCleaningEventsService = new CottagesCleaningEventsService($this->em);
        $cottagesCleaningEventsService->createCottageEventRecord($createdEvent, $data['cottage']);
    }

    /**
     * @return EventParser
     */
    public function getEvent(): EventParser
    {
        return new CleaningParser($this->em);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return self::EVENT_TYPE;
    }
}