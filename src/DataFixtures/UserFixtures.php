<?php
declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Salary;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct
    (
        private readonly UserPasswordHasherInterface $passwordHasher
    ){
    }

    public function load(ObjectManager $manager): void
    {
        $mount = getenv('MOUNT') ?: 2;
        $users = getenv('USERS') ?: 2;

        for ($i = 1; $i <= $users; $i++) {
            $user = new User();
            $user->setEmail("mail{$i}@example.com");
            $user->setRoles($i === 1 ? ['ROLE_ADMIN'] : []);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                'XCrudUserPassword123!'
            ));
            $user->setName("TestName{$i}");
            $user->setSurname("TestSurName{$i}");
            $user->setEmploymentDate((new \DateTime())->modify("-{$mount} month"));
            if ($i !== 1) {
                for ($j = 0; $j < $mount; $j++) {
                    $salary = new Salary();
                    $randomSalary = number_format(mt_rand(100, 4500), 2, '.', '');
                    $salary->setAmount($randomSalary);
                    $salary->setPaymentDate((new \DateTimeImmutable())->modify("-{$j} month"));

                    $user->addSalary($salary);
                }
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}