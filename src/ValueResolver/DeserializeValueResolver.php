<?php
declare(strict_types=1);

namespace App\ValueResolver;

use App\Attribute\Deserialize;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Psr\Log\LoggerInterface;

readonly class DeserializeValueResolver implements ValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface  $validator,
        private LoggerInterface     $logger
    ) { }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(Deserialize::class, ArgumentMetadata::IS_INSTANCEOF);

        if (empty($attributes)) {
            return [];
        }

        if (!in_array($request->getMethod(), ['POST', 'PUT', 'PATCH'], true)) {
            $this->logger->info('DeserializeValueResolver: HTTP method not supported for argument ' . $argument->getName());
            return [];
        }

        if (null === $argument->getType()) {
            $this->logger->error('DeserializeValueResolver: Argument type is null for argument ' . $argument->getName());
            throw new BadRequestHttpException('Argument type is not specified.');
        }

        $dtoClass = $argument->getType();
        $content = $request->getContent();

        $this->logger->info('DeserializeValueResolver: Received request content: ' . $content);

        if (empty($content)) {
            $this->logger->warning('DeserializeValueResolver: Empty request body for argument ' . $argument->getName());
            throw new BadRequestHttpException('Empty request body.');
        }

        try {
            /** @var object $dto */
            $dto = $this->serializer->deserialize($content, $dtoClass, 'json');
        } catch (\Exception $e) {
            $this->logger->error('DeserializeValueResolver: Deserialization failed: ' . $e->getMessage());
            throw new BadRequestHttpException('Invalid JSON format.');
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }

            $this->logger->warning('DeserializeValueResolver: Validation errors: ' . json_encode($errorMessages));

            throw new BadRequestHttpException(json_encode(['errors' => $errorMessages]));
        }

        yield $dto;
    }
}