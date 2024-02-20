<?php

namespace App\Controller;

use App\Entity\Salary;
use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\SalaryRepository;
use App\Repository\TimestampsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly SalaryRepository            $salaryRepository,
        private readonly SerializerInterface         $serializer,
        private readonly ValidatorInterface          $validator,
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepository              $userRepository,
        private readonly TimestampsRepository        $timestampsRepository
    ){ }

    #[Route('/show/all', name: 'user_show_all', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns all users',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['list']))
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function showAll(): JsonResponse
    {
        $users = $this->userRepository->findAllWithTotalSalary($this->timestampsRepository);
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'list']);
    }

    #[Route('/{id}', name: 'user_show', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Returns a user',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['list']))
    )]
    #[OA\Response(
        response: 404,
        description: 'User not found',
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        $totalSalary = $this->salaryRepository->findTotalSalaryByUser($user->getId());
        $user->setTotalSalary($totalSalary);
        $user->addLatestTimestamp($user->getId(), User::class, $this->timestampsRepository);
        $user->getSalaries()->map(function (Salary $salary) {
            $salary->addLatestTimestamp($salary->getId(), Salary::class, $this->timestampsRepository);
        });
        return $this->json($user, 200, [], ['groups' => 'list']);
    }

    #[Route('/new', name: 'user_new', methods: ['POST'])]
    #[OA\RequestBody(
        description: "User's data",
        required: true,
        content: new Model(type: User::class, groups: ["create"])
    )]
    #[OA\Response(
        response: 200,
        description: 'Creates a new user',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['create']))
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request - Invalid argument or User already exists',
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function new(Request $request): JsonResponse
    {
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user->setEmploymentDate(new \DateTimeImmutable());
        $errors = $this->validator->validate($user, null, ['create']);
        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new InvalidArgumentException(json_encode(['errors' => $errorsArray]), 400);
        }

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_CREATED, [], ['groups' => 'list']);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['PUT'])]
    #[OA\RequestBody(
        description: "User's data",
        required: true,
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ["update"]))
    )]
    #[OA\Response(
        response: 200,
        description: 'Edits an existing user',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['update']))
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad Request - Invalid argument',
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function edit(Request $request, int $id): JsonResponse
    {
        $existingUser = $this->userRepository->find($id);
        if (!$existingUser) {
            throw new UserNotFoundException('User not found');
        }

        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $existingUser]);
        $errors = $this->validator->validate($user, null, ['update']);

        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new InvalidArgumentException(json_encode(['errors' => $errorsArray]), 400);
        }

        if ($user->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashedPassword);
        }

        $this->entityManager->flush();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => 'update']);
    }


    #[Route('/{id}', name: 'user_delete', methods: ['DELETE'])]
    #[OA\Response(
        response: 200,
        description: 'Deletes a user',
        content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['full']))
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->json(['message' => 'User deleted successfully']);
    }

    #[Route('/{id}/add-salary', name: 'user_add_salary', methods: ['POST'])]
    #[OA\Post(
        description: 'Adds a new salary record to the specified user.',
        summary: 'Add salary to a user',
        requestBody: new OA\RequestBody(
            description: 'Salary data',
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "amount", type: "string", example: "1234.56"),
                        new OA\Property(property: "paymentDate", type: "string", format: "date", example: "2022-01-01"),
                    ],
                    type: 'object',
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Salary added successfully',
                content: new OA\JsonContent(
                    properties: [
                        'message' => new OA\Property(type: 'string', example: 'Salary added successfully'),
                    ],
                    type: 'object'
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        'error' => new OA\Property(type: 'string', example: 'User not found'),
                    ],
                    type: 'object'
                )
            ),
        ]
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function addSalary(Request $request, int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }
        $salary = $this->serializer->deserialize($request->getContent(), Salary::class, 'json');
        $salary->setUser($user);
        $errors = $this->validator->validate($salary, null, ['payment']);

        if (count($errors) > 0) {
            $errorsArray = [];
            foreach ($errors as $error) {
                $errorsArray[$error->getPropertyPath()][] = $error->getMessage();
            }

            throw new InvalidArgumentException(json_encode(['errors' => $errorsArray]), 400);
        }

        $this->entityManager->persist($salary);
        $this->entityManager->flush();

        return $this->json($salary, Response::HTTP_CREATED, [], ['groups' => 'payment_response']);
    }
}
