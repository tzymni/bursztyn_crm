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
            ->select('p.id, p.email, p.first_name, p.last_name, p.days_before_notification, p.is_active')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', true)
            ->getQuery();

        return $qb->execute();
    }

    /**
     * Get all users with enabled email cleaning notifications.
     *
     * @return array
     */
    public function findUsersWithEnabledNotifications(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id, p.email, p.days_before_notification, p.is_active, p.first_name')
            ->andWhere('p.is_active = :active')
            ->andWhere('p.days_before_notification > :disabled_notifications')
            ->setParameter('active', true)
            ->setParameter('disabled_notifications', 0)
            ->getQuery();

        return $qb->execute();
    }
}