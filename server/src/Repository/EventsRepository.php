<?php

namespace App\Repository;

use App\Entity\Events;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Events|null find($id, $lockMode = null, $lockVersion = null)
 * @method Events|null findOneBy(array $criteria, array $orderBy = null)
 * @method Events[]    findAll()
 * @method Events[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Events::class);
    }

    /**
     * Find all active cottages/
     *
     * @return array
     */
    public function findAllActive(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id,p.date_from_unix_utc, p.date_to_unix_utc, p.title, p.is_active, p.type')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', true)
            ->getQuery();

        return $qb->execute();
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

}
