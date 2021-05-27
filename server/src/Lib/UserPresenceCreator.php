<?php

namespace App\Lib;

use App\Entity\Users;
use App\Service\UserPresenceService;
use App\Service\UsersService;
use http\Exception\InvalidArgumentException;

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

        } else {
            throw new InvalidArgumentException('Validation date error.');
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
        $userId = $data['user_id'];
        $userService = new UsersService($this->em);
        $user = $userService->getActiveUserById($userId);

        if(empty($userId) || !($user instanceof Users)) {
            return false;
        }

        return true;
    }

    public function getEvent(): EventParser
    {
        return new UserPresenceParser($this->em);
    }
}