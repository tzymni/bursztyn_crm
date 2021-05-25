<?php

namespace App\Lib;

use App\Entity\UserPresences;
use App\Service\UsersService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserPresenceParser to parse UserPresence data before save it into the database.
 *
 * @package App\Lib
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UserPresenceParser implements EventParser
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
     * Parse event data.
     *
     * @param $data
     * @return array
     */
    public function parseData($data): array
    {

        $data['type'] = UserPresences::EVENT_TYPE;
        $data['title'] = $this->generateTitle($data);
        $data['date_to'] = strtotime($data['date_to'] . ' UTC');
        $data['date_from'] = strtotime($data['date_from'] . ' UTC');
        $data['is_active'] = true;

        return $data;
    }

    /**
     * Generate title of the event.
     *
     * @param $data
     * @return string
     */
    public function generateTitle($data): string
    {
        $userId = $data['user_id'];

        $userService = new UsersService($this->em);
        $user = $userService->getActiveUserById($userId);
        return sprintf("Obecność: %s (%s)", $user->getFirstName(), $data['extra_info']);
    }
}