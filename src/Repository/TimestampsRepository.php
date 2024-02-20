<?php

namespace App\Repository;

use App\Entity\Timestamps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Timestamps>
 *
 * @method Timestamps|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timestamps|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timestamps[]    findAll()
 * @method Timestamps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimestampsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timestamps::class);
    }

    public function findByEntity(int $entityId, string $entityClass): ?Timestamps
    {
        return $this->findOneBy([
            'entityId' => $entityId,
            'entityClass' => $entityClass,
        ]);
    }

}
