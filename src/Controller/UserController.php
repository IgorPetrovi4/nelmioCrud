<?php

namespace App\Controller;

use App\Attribute\Deserialize;
use App\DTO\Request\UserCreateRequestDTO;
use App\DTO\Response\UserResponseDTO;
use App\Entity\User;
use App\Mapper\EntityMapperInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('api/user')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository               $userRepository,
        private readonly EntityMapperInterface        $entityMapper,
        private readonly EntityManagerInterface       $entityManager,
        private readonly UserPasswordHasherInterface  $passwordHasher
    ) { }

    #[Route('/show/all', name: 'user_show_all', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Возвращает всех пользователей',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: UserResponseDTO::class, groups: ['list']))
        )
    )]
    #[OA\Tag(name: 'users')]
    #[Security(name: 'Bearer')]
    public function showAll(): JsonResponse
    {
        $users = $this->userRepository->findAllWithTotalSalary();
        $userDTOs = $this->entityMapper->mapToDTOs($users, UserResponseDTO::class, ['list']);

        return $this->json($userDTOs, Response::HTTP_OK, [], ['groups' => 'list']);
    }

    #[Route('/create', name: 'user_create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/user/create',
        description: 'Создает нового пользователя',
        summary: 'Создание пользователя',
        requestBody: new OA\RequestBody(
            description: "Данные для создания пользователя",
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserCreateRequestDTO::class)
            )
        ),
        tags: ['users'],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Пользователь успешно создан',
                content: new OA\JsonContent(ref: new Model(type: UserResponseDTO::class, groups: ['list']))
            ),
            new OA\Response(
                response: 400,
                description: 'Некорректные входные данные',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'))
                    ]
                )
            )
        ]
    )]
    #[Security(name: 'Bearer')]
    public function create(#[Deserialize] UserCreateRequestDTO $userCreateRequestDTO): JsonResponse
    {
        $user = $this->entityMapper->mapToEntity($userCreateRequestDTO, User::class);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $userCreateRequestDTO->password);
        $user->setPassword($hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userDTO = $this->entityMapper->mapToDTO($user, UserResponseDTO::class, ['list']);

        return $this->json($userDTO, Response::HTTP_CREATED, [], ['groups' => 'list']);
    }

    #[Route('/{id}/edit', name: 'user_edit', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/user/{id}/edit',
        description: 'Редактирует существующего пользователя',
        summary: 'Редактирование пользователя',
        requestBody: new OA\RequestBody(
            description: "Данные для обновления пользователя",
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: UserCreateRequestDTO::class) // Рекомендуется создать отдельный DTO для обновления
            )
        ),
        tags: ['users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Пользователь успешно обновлен',
                content: new OA\JsonContent(ref: new Model(type: UserResponseDTO::class, groups: ['list']))
            ),
            new OA\Response(
                response: 400,
                description: 'Некорректные входные данные',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'errors', type: 'array', items: new OA\Items(type: 'string'))
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Пользователь не найден'
            )
        ]
    )]
    #[Security(name: 'Bearer')]
    public function edit(#[Deserialize] UserCreateRequestDTO $userUpdateRequestDTO, User $user): JsonResponse
    {
        $user = $this->entityMapper->mapToEntity($userUpdateRequestDTO, User::class, $user);

        if (!empty($userUpdateRequestDTO->password)) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $userUpdateRequestDTO->password);
            $user->setPassword($hashedPassword);
        }

        $this->entityManager->flush();
        $userDTO = $this->entityMapper->mapToDTO($user, UserResponseDTO::class, ['list']);

        return $this->json($userDTO, Response::HTTP_OK, [], ['groups' => 'list']);
    }
}
