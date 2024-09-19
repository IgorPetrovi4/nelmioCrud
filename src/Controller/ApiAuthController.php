<?php

declare(strict_types=1);

namespace App\Controller;

use App\Attribute\Deserialize;
use App\DTO\Request\LoginRequest;
use App\Service\Auth\Interface\AuthServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiAuthController extends AbstractController
{
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) { }

    #[Route('/api/login', methods: ['POST'])]
    #[OA\Post(
        path: '/api/login',
        description: 'Возвращает токен пользователя при успешной аутентификации',
        summary: 'Аутентификация пользователя',
        requestBody: new OA\RequestBody(
            description: "Учетные данные пользователя",
            required: true,
            content: new OA\JsonContent(
                ref: new Model(type: LoginRequest::class)
            )
        ),
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешная аутентификация',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'token',
                            type: 'string',
                            example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Некорректные входные данные',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'errors',
                            type: 'array',
                            items: new OA\Items(type: 'string')
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Неверные учетные данные',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'Неверные учетные данные.'
                        )
                    ]
                )
            )
        ]
    )]
    #[Security(name: 'Bearer')]
    public function login(#[Deserialize] LoginRequest $loginRequest): JsonResponse
    {
        try {
            $token = $this->authService->token(
                $loginRequest->getEmail(),
                $loginRequest->getPassword()
            );
        } catch (AuthenticationException $e) {
            return $this->json(['message' => $e->getMessage()], JsonResponse::HTTP_UNAUTHORIZED);
        }

        return $this->json(['token' => $token]);
    }
}