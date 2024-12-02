<?php

namespace App\Repository;

use App\Entity\Salary;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }


//    public function findAllWithTotalSalary(TimestampsRepository $timestampsRepository): iterable
//    {
//        $queryBuilder = $this->createQueryBuilder('u')
//            ->addSelect('SUM(s.amount) as totalSalary')
//            ->leftJoin('u.salaries', 's')
//            ->groupBy('u.id');
//
//        $users = $queryBuilder->getQuery()->getResult();
//
//        foreach ($users as $user) {
//            foreach ($user[0]->getSalaries() as $salary) {
//                $salary->addLatestTimestamp($salary->getId(), Salary::class, $timestampsRepository );
//            }
//            yield $user[0]
//                ->setTotalSalary($user['totalSalary'])
//                ->addLatestTimestamp($user[0]->getId(), User::class, $timestampsRepository);
//        }
//
//    }

    /**
     * Возвращает всех пользователей с общей зарплатой.
     *
     * @return User[]
     */
    public function findAllWithTotalSalary(): array
    {
        $qb = $this->createQueryBuilder('u')
            ->leftJoin('u.salaries', 's')
            ->addSelect('SUM(s.amount) AS totalSalary')
            ->groupBy('u.id');

        $result = $qb->getQuery()->getResult();

        foreach ($result as $row) {
            /** @var User $user */
            $user = $row[0];
            $user->setTotalSalary($row['totalSalary'] !== null ? (string)$row['totalSalary'] : '0');
        }

        return array_map(function($row) {
            return $row[0];
        }, $result);
    }

}
