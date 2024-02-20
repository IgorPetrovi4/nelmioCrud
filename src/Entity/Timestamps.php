<?php

namespace App\Entity;

use App\Repository\TimestampsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TimestampsRepository::class)]
#[ORM\UniqueConstraint(name: "unique_entity", columns: ["entity_id", "entity_class"])]
#[Index(name: "idx_entity_id_class", columns: ["entity_id", "entity_class"])]
#[ORM\HasLifecycleCallbacks]
class Timestamps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("list")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups("list")]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups("list")]
    private ?string $timezone = null;

    #[ORM\Column]
    private ?int $entityId = null;

    #[ORM\Column(length: 255)]
    private ?string $entityClass = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): static
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entityId;
    }

    public function setEntityId(int $entityId): static
    {
        $this->entityId = $entityId;

        return $this;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(string $entityClass): static
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
