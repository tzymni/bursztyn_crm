<?php

namespace App\Service;

use App\Entity\Events;
use App\Entity\UserPresences;
use App\Repository\UserPresencesRepository;
use App\Service\interfaces\DecorateEventInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserPresenceService implements DecorateEventInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ReservationService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createUserPresence(Events $event, array $data, $userPresence = null)
    {

        if (empty($userPresence) || !$userPresence instanceof UserPresences) {
            $userPresence = new UserPresences();
        }

        $userService = new UsersService($this->em);
        $user = $userService->getActiveUserById($data['user_id']);

        $dateAdd = $data['date_add'] ?? gmdate("Y-m-d H:i:s");
        $dateAdd = \DateTime::createFromFormat("Y-m-d H:i:s", $dateAdd);

        $userPresence->setDateAdd($dateAdd);
        $userPresence->setEvent($event);
        $userPresence->setExtraInfo($data['extra_info']);
        $userPresence->setUser($user);

        $this->em->persist($userPresence);

        $this->em->flush();
    }

    /**
     * @param int $eventId
     * @return mixed|void
     */
    public function getEventDetails(int $eventId)
    {
        return $this->getActiveUserPresenceByEventId($eventId);
    }

    /**
     * @param int $eventId
     * @return mixed
     */
    public function getActiveUserPresenceByEventId(int $eventId)
    {

        $userPresenceRepository = $this->em->getRepository(UserPresences::class);

        $userPresence = array();

        if ($userPresenceRepository instanceof UserPresencesRepository) {

            $userPresence = $userPresenceRepository->findActiveUserPresenceByEventId($eventId);
        }

        return $userPresence[0];

    }
}