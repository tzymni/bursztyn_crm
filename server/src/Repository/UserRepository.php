<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     *
     * @return User[]
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