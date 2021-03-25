<?php

namespace App\Repository;

use App\Entity\Cottages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class CottagesRepository
 * @package App\Repository
 *
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class CottagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cottages::class);
    }

    /**
     * Find all active cottages/
     *
     * @return array
     */
    public function findAllActive(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select(array())
            ->andWhere('p.is_active = :active')
            ->setParameter('active', 1)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * Find active cottage by id.
     *
     * @param $id
     * @return object|null
     */
    public function findActiveById($id): ?object
    {
        $cottage = $this->findBy(
            array("is_active" => true, "id" => $id),
            array(),
            array(1)
        );

        if (isset($cottage) && isset($cottage[0])) {
            return $cottage[0];
        } else {
            return null;
        }
    }

    /**
     * Find cottage by id.
     *
     * @param $externalId
     * @return object|null
     */
    public function findByExternalId($externalId): ?object
    {
        $cottage = $this->findOneBy(['external_id' => $externalId]);

        if ($cottage) {
            return $cottage;
        } else {
            return null;
        }
    }

    /**
     * Find all colors of active cottages.
     *
     * @return int|mixed|string
     */
    public function findAllColorsOfActiveCottages()
    {
        $query = $this->createQueryBuilder('e')
            ->select('e.color')
            ->andWhere('e.is_active = :is_active')
            ->setParameter('is_active', true);

        return $query->getQuery()->getResult();
    }

    /**
     * Find all cottages with external_id and active status.
     *
     * @return int|mixed|string
     */
    public function findAllExternalActiveCottages()
    {
        $query = $this->createQueryBuilder('e')
            ->select(array())
            ->andWhere('e.is_active = :is_active')
            ->andWhere('e.external_id IS NOT NULL')
            ->setParameter('is_active', true);
        return $query->getQuery()->getResult();
    }

}
