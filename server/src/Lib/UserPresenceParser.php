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
        $dateTo = substr($data['date_to'], 0, 10)." 23:59:59"; ;
        $data['date_to'] = strtotime($dateTo . ' UTC');
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

        $extraInfo = $data['extra_info'];

        if($extraInfo) {
            $title = sprintf("%s. (%s - %s) (%s).", $user->getFirstName(), $data['date_from'], $data['date_to'], $data['extra_info']);
        } else {
            $title = sprintf("%s. (%s - %s).", $user->getFirstName(), $data['date_from'], $data['date_to'],);
        }

        return $title;
    }
}