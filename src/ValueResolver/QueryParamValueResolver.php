<?php
declare(strict_types=1);

namespace App\ValueResolver;

use App\Attribute\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Psr\Log\LoggerInterface;

readonly class QueryParamValueResolver implements ValueResolverInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private LoggerInterface    $logger
    ) { }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(QueryParam::class, ArgumentMetadata::IS_INSTANCEOF);

        if (empty($attributes)) {
            return [];
        }

        if (null === $argument->getType()) {
            $this->logger->error('QueryParamValueResolver: Argument type is null for argument ' . $argument->getName());
            throw new BadRequestHttpException('Argument type is not specified.');
        }

        $dtoClass = $argument->getType();
        $data = $request->query->all();

        try {
            /** @var object $dto */
            $dto = new $dtoClass();

            foreach ($data as $key => $value) {
                $setter = 'set' . ucfirst($key);
                if (method_exists($dto, $setter)) {
                    $dto->{$setter}($value);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('QueryParamValueResolver: Mapping failed: ' . $e->getMessage());
            throw new BadRequestHttpException('Invalid query parameters.');
        }

        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getPropertyPath() . ': ' . $error->getMessage();
            }

            $this->logger->warning('QueryParamValueResolver: Validation errors: ' . json_encode($errorMessages));

            throw new BadRequestHttpException(json_encode(['errors' => $errorMessages]));
        }

        yield $dto;
    }
}
