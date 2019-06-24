<?php


namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;



class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
    /**
     * 
     * @return User[]
     */
    public function findAllActiveWithoutPassword(): array
    {

        $qb = $this->createQueryBuilder('p')
            ->select('p.email, p.first_name, p.last_name')
            ->andWhere('p.is_active = :active')
            ->setParameter('active', 1)
            ->getQuery();

        return $qb->execute();


    }
    
    
}