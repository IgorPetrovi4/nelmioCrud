<?php
declare(strict_types=1);

namespace App\Mapper;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Doctrine\ORM\EntityManagerInterface;

class EntityMapper implements EntityMapperInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        //private EntityManagerInterface $entityManager
    ){ }

    public function mapToEntity(object $dto, string $entityClass, object $entity = null): object
    {
        $context = [
            AbstractNormalizer::OBJECT_TO_POPULATE => $entity,
            'allow_extra_attributes' => false,
        ];

        return $this->serializer->denormalize($dto, $entityClass, null, $context);
    }

    public function mapToDTO(object $entity, string $dtoClass, array $groups = []): object
    {
        $context = [];
        if (!empty($groups)) {
            $context['groups'] = $groups;
        }

        $data = $this->serializer->normalize($entity, null, $context);

        return $this->serializer->denormalize($data, $dtoClass, null, $context);
    }

    public function mapToDTOs(iterable $entities, string $dtoClass, array $groups = []): array
    {
        $dtos = [];
        foreach ($entities as $entity) {
            $dtos[] = $this->mapToDTO($entity, $dtoClass, $groups);
        }

        return $dtos;
    }
}
