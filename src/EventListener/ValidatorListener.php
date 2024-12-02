<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Attribute\Validator as ValidatorAttribute;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

readonly class ValidatorListener
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        $controller = $event->getController();

        if (!is_array($controller)) {
            return;
        }

        $reflection = new \ReflectionMethod($controller[0], $controller[1]);
        $arguments = $event->getArguments();

        foreach ($reflection->getParameters() as $index => $parameter) {
            $attributes = $parameter->getAttributes(ValidatorAttribute::class);

            if (!empty($attributes)) {
                $dto = $arguments[$index];
                $errors = $this->validator->validate($dto);

                if (count($errors) > 0) {
                    $errorMessages = [];
                    /** @var ConstraintViolationListInterface $errors */
                    foreach ($errors as $error) {
                        $errorMessages[] = $error->getMessage();
                    }

                    $event->setController(function () use ($errorMessages) {
                        return new JsonResponse(['errors' => $errorMessages], 400);
                    });
                    return;
                }
            }
        }
    }
}
