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

    public function searchUsers(string $name = '', string $email = '', string $password = '', string $lastActive = ''): array
    {
        $qb = $this->createQueryBuilder('u');

        if ($name !== '') {
            $qb->andWhere('LOWER(u.name) LIKE LOWER(:name)')
               ->setParameter('name', '%' . $name . '%');
        }
        if ($email !== '') {
            $qb->andWhere('LOWER(u.email) LIKE LOWER(:email)')
               ->setParameter('email', '%' . $email . '%');
        }
        if ($password !== '') {
            $qb->andWhere('LOWER(u.plainPassword) LIKE LOWER(:password)')
               ->setParameter('password', '%' . $password . '%');
        }
        if ($lastActive !== '') {
            $qb->andWhere('LOWER(u.lastActive) LIKE LOWER(:lastActive)')
               ->setParameter('lastActive', '%' . $lastActive . '%');
        }

        return $qb->orderBy('u.id', 'DESC')->getQuery()->getResult();
    }
}
