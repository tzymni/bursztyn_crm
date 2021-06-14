<?php

namespace App\Repository;

use App\Entity\Events;
use App\Entity\UserPresences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserPresences|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserPresences|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserPresences[]    findAll()
 * @method UserPresences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPresencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPresences::class);
    }

    /**
     * @param $eventId
     * @return int|mixed|string
     */
    public function findActiveUserPresenceByEventId($eventId)
    {
        return $this->createQueryBuilder('p')
            ->select(
                'p.id as user_presence_id, p.extra_info,  Users.id as user_id  '
            )
            ->andWhere('p.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->leftJoin('p.user', 'Users')
            ->getQuery()->execute();
    }

    /**
     * @param Events $event
     * @return UserPresences[]
     */
    public function findActiveUserPresenceByEvent(Events $event)
    {
        return $this->findBy(
            array("event" => $event),
            array(),
            array(1));
    }

}
