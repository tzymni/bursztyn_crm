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
            ->select('p.id,p.name, p.color, p.extra_info, p.max_guests_number, p.is_active')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', 1)
            ->getQuery();

        return $qb->execute();
    }

}
