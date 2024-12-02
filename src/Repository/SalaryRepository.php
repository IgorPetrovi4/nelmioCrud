<?php

namespace App\Repository;

use App\Entity\Salary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Salary>
 *
 * @method Salary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Salary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Salary[]    findAll()
 * @method Salary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Salary::class);
    }

    public function findTotalSalaryByUser(int $userId): ?string
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->select('SUM(s.amount) as totalSalary')
            ->where('s.user = :userId')
            ->andWhere('s.user IS NOT NULL')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleResult();

        return $queryBuilder['totalSalary'];
    }

}
