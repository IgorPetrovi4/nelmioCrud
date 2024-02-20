<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ApiAuthController extends AbstractController
{
    #[Route('/api/login', methods: ['POST'])]
    #[OA\Response(
        response: 200,
        description: 'Returns the user token upon successful authentication',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...'),
            ]
        )
    )]
    #[OA\RequestBody(
        description: "User's credentials",
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'email', type: 'string', example: 'mail1@example.com'),
                new OA\Property(property: 'password', type: 'string', example: 'XCrudUserPassword123!'),
            ]
        )
    )]
    #[OA\Tag(name: 'Authentication')]
    #[Security(name: 'Bearer')]
    public function login(Request $request, JWTTokenManagerInterface $JWTManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $userRepository->findOneByEmail($email);

        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return $this->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $JWTManager->create($user);

        return $this->json(['token' => $token]);
    }
}
