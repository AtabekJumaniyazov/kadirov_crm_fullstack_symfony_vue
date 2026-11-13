<?php

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function searchCompanies(string $name = '', string $email = '', string $password = '', string $lastActive = ''): array
    {
        $qb = $this->createQueryBuilder('c');

        if ($name !== '') {
            $qb->andWhere('LOWER(c.name) LIKE LOWER(:name)')
               ->setParameter('name', '%' . $name . '%');
        }
        if ($email !== '') {
            $qb->andWhere('LOWER(c.email) LIKE LOWER(:email)')
               ->setParameter('email', '%' . $email . '%');
        }
        if ($password !== '') {
            $qb->andWhere('LOWER(c.password) LIKE LOWER(:password)')
               ->setParameter('password', '%' . $password . '%');
        }
        if ($lastActive !== '') {
            $qb->andWhere('LOWER(c.lastActive) LIKE LOWER(:lastActive)')
               ->setParameter('lastActive', '%' . $lastActive . '%');
        }

        return $qb->orderBy('c.id', 'DESC')->getQuery()->getResult();
    }
}
