<?php
declare(strict_types=1);

namespace App\Mapper;

use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface EntityMapperInterface
{
    /**
     * Преобразует DTO в сущность Doctrine.
     *
     * @param object $dto Объект DTO.
     * @param string $entityClass Класс сущности Doctrine.
     * @param object|null $entity Существующая сущность для обновления (опционально).
     * @return object Сущность Doctrine.
     *
     * @throws ExceptionInterface Если сериализация не удалась.
     */
    public function mapToEntity(object $dto, string $entityClass, object $entity = null): object;

    /**
     * Преобразует сущность Doctrine в DTO.
     *
     * @param object $entity Сущность Doctrine.
     * @param string $dtoClass Класс DTO.
     * @param array $groups Группы сериализации (опционально).
     * @return object DTO.
     *
     * @throws ExceptionInterface Если сериализация не удалась.
     */
    public function mapToDTO(object $entity, string $dtoClass, array $groups = []): object;

    /**
     * Преобразует коллекцию сущностей Doctrine в массив DTO.
     *
     * @param iterable $entities Коллекция сущностей Doctrine.
     * @param string $dtoClass Класс DTO.
     * @param array $groups Группы сериализации (опционально).
     * @return array Массив DTO.
     *
     * @throws ExceptionInterface Если сериализация не удалась.
     */
    public function mapToDTOs(iterable $entities, string $dtoClass, array $groups = []): array;
}
