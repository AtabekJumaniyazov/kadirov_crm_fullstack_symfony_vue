<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function searchCustomers(
        string $name = '',
        string $email = '',
        string $password = '',
        string $workplace = '',
        string $lastActive = '',
        ?int $companyId = null
    ): array {
        $qb = $this->createQueryBuilder('cu')
                   ->leftJoin('cu.company', 'co')
                   ->addSelect('co');

        if ($name !== '') {
            $qb->andWhere('LOWER(cu.name) LIKE LOWER(:name)')
               ->setParameter('name', '%' . $name . '%');
        }
        if ($email !== '') {
            $qb->andWhere('LOWER(cu.email) LIKE LOWER(:email)')
               ->setParameter('email', '%' . $email . '%');
        }
        if ($password !== '') {
            $qb->andWhere('LOWER(cu.password) LIKE LOWER(:password)')
               ->setParameter('password', '%' . $password . '%');
        }
        if ($workplace !== '') {
            $qb->andWhere('LOWER(co.name) LIKE LOWER(:workplace)')
               ->setParameter('workplace', '%' . $workplace . '%');
        }
        if ($lastActive !== '') {
            $qb->andWhere('LOWER(cu.lastActive) LIKE LOWER(:lastActive)')
               ->setParameter('lastActive', '%' . $lastActive . '%');
        }
        if ($companyId !== null && $companyId > 0) {
            $qb->andWhere('co.id = :companyId')
               ->setParameter('companyId', $companyId);
        }

        return $qb->orderBy('cu.id', 'DESC')->getQuery()->getResult();
    }
}
