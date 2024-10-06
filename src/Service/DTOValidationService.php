<?php
declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Exception\ValidationFailedException;

readonly class DTOValidationService implements DTOValidationServiceInterface
{
    public function __construct(
        private ValidatorInterface $validator
    )
    {
    }

    public function validate(object $dto): void
    {
        $errors = $this->validator->validate($dto, new Valid());
        if (count($errors) > 0) {
            throw new ValidationFailedException($dto, $errors);
        }
    }

    public function formatValidationErrors(ConstraintViolationListInterface $errors): string
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
        }
        return implode("\n", $errorMessages);
    }
}
