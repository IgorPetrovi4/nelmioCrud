<?php
declare(strict_types=1);


namespace App\Entity;

use App\Repository\TimestampsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractLatestTimestamp
{

    #[Groups("list")]
    private Collection $latestTimestamps;

    /**
     * Addes a timestamp to the latestTimestamps collection.
     *
     * @param int    $entityId    Identifier of the entity
     * @param string $entityClass  Class name of the entity
     */
    public function addLatestTimestamp(int $entityId, string $entityClass, TimestampsRepository $timestampsRepository): static
    {
        $this->latestTimestamps = new ArrayCollection();
        $timestamp = $timestampsRepository->findByEntity($entityId, $entityClass);
        if ($timestamp !== null) {
            $this->latestTimestamps->add($timestamp);
        }

        return $this;
    }

    public function getLatestTimestamps(): Collection
    {
        return $this->latestTimestamps;
    }


}