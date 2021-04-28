<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{

    /**
     * EventsRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    /**
     * Find all active cottages.
     *
     * @param null $type
     * @return array
     */
    public function findActiveEvents($type = null): array
    {
        if (empty($type) || $type == 'ALL') {
            $conditions = array("is_active" => true);

        } else {
            $conditions = array("is_active" => true, "type" => $type);
        }

        return $this->findBy($conditions, array());
    }

    /**
     * Find active event by id.
     *
     * @param $id
     * @return object|string
     */
    public function findActiveById($id)
    {
        $event = $this->findBy(
            array("is_active" => true, "id" => $id),
            array(),
            array(1)
        );

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {
            return null;
        }
    }

    /**
     * Find active event by date and type.
     *
     * @param $type
     * @param $dateFrom
     * @param $dateTo
     * @return object|string
     */
    public function findActiveEventByDateAndType($type, $dateFrom, $dateTo)
    {
        $event = $this->findBy(
            array("is_active" => true, "type" => $type, "date_from" => $dateFrom, "date_to" => $dateTo),
            array(),
            array(1)
        );

        if (isset($event) && isset($event[0])) {
            return $event[0];
        } else {
            return sprintf("Can't find event!");
        }
    }

    /**
     * Find active events with date_from >= now.
     *
     * @param $type
     * @return int|mixed|string|null
     */
    public function findActiveNextEventsByType($type)
    {

        $dateFrom = gmdate("Y-m-d 00:00:00");

        $query = $this->createQueryBuilder('e')
            ->select(array())
            ->where('e.is_active = :isActive')
            ->andWhere('e.type = :type')
            ->andWhere('e.date_from >= :dateFrom')
            ->orderBy('e.date_from ', 'ASC')
            ->setParameter('isActive', true)
            ->setParameter('type', $type)
            ->setParameter('dateFrom', $dateFrom);

        $events = $query->getQuery()->getResult();

        if (isset($events) && isset($events[0])) {
            return $events;
        } else {
            return null;
        }
    }
}
