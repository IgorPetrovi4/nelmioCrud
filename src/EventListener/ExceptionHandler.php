<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\UserNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;


class ExceptionHandler
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof UserNotFoundException) {
            $response = new JsonResponse([
                'response' => 404,
                'description' => 'User not found',
            ], 404);

            $event->setResponse($response);
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            $response = new JsonResponse([
                'response' => 400,
                'description' => 'User already exists',
            ], 400);

            $event->setResponse($response);
        }

        if ($exception instanceof InvalidArgumentException) {
            $messageData = json_decode($exception->getMessage(), true);

            $response = new JsonResponse([
                'status' => 400,
                'description' => 'Invalid argument',
                'errors' => $messageData['errors'] ?? [],
            ], 400);

            $event->setResponse($response);
        }

    }
}