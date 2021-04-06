<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UsersRepository
 *
 * @package App\Repository
 * @author Tomasz Zymni <tomasz.zymni@gmail.com>
 */
class UsersRepository extends ServiceEntityRepository
{
    /**
     * UsersRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Find all active users.
     *
     * @return Users[]
     */
    public function findAllActiveUsers(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.email, p.first_name, p.last_name, p.is_active')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', true)
            ->getQuery();

        return $qb->execute();
    }
}