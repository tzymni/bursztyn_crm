<?php

namespace App\Service\interfaces;

/**
 * Interface DecorateEventInterface
 * @package App\Service\interfaces
 */
interface DecorateEventInterface
{
    /**
     * Get event details.
     *
     * @param int $eventId
     * @return mixed
     */
    public function getEventDetails(int $eventId);
}