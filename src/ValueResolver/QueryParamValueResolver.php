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
        // Проверяем наличие атрибута #[QueryParam] на аргументе
        $attributes = $argument->getAttributes(QueryParam::class, ArgumentMetadata::IS_INSTANCEOF);

        if (empty($attributes)) {
            return [];
        }

        // Убедитесь, что тип аргумента указан
        if (null === $argument->getType()) {
            $this->logger->error('QueryParamValueResolver: Argument type is null for argument ' . $argument->getName());
            throw new BadRequestHttpException('Argument type is not specified.');
        }

        $dtoClass = $argument->getType();

        // Извлекаем данные из query параметров
        $data = $request->query->all();

        try {
            /** @var object $dto */
            $dto = new $dtoClass();

            // Предполагается, что DTO имеет сеттеры для свойств
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

        // Валидация DTO
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
