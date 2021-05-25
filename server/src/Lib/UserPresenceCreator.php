<?php

namespace App\Lib;

use App\Service\UserPresenceService;

/**
 * Class UserPresenceCreator
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UserPresenceCreator extends EventCreator
{

    /**
     * @var UserPresenceService
     */
    protected $userPresenceService;

    /**
     * @param $data
     * @throws Exception
     */
    public function create($data)
    {
        $this->userPresenceService = new UserPresenceService($this->em);
        $data = $this->getEvent()->parseData($data);

        if ($this->validateData($data)) {

            $createdEvent = parent::create($data);

            $this->userPresenceService->createUserPresence($createdEvent, $data);

        }
    }

    /**
     * Valid reservation data.
     *
     * @param $data
     * @return bool
     * @throws Exception
     */
    private function validateData($data): bool
    {

        return true;

    }

    public function getEvent(): EventParser
    {
        return new UserPresenceParser($this->em);
    }
}