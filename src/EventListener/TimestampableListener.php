<?php
declare(strict_types=1);


namespace App\EventListener;


use App\Entity\AbstractLatestTimestamp;
use App\Entity\Timestamps;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TimestampableListener
{
    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof AbstractLatestTimestamp) {
            $entityManager = $args->getObjectManager();
            $timestamps = new Timestamps();

            $timestamps->setEntityId($entity->getId());
            $timestamps->setEntityClass(get_class($entity));
            $timestamps->setCreatedAt(new \DateTimeImmutable());
            $timestamps->setUpdatedAt(new \DateTimeImmutable());
            $timestamps->setTimezone(date_default_timezone_get());

            $entityManager->persist($timestamps);
            $entityManager->flush();
        }
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof AbstractLatestTimestamp) {
            $entityManager = $args->getObjectManager();
            $repository = $entityManager->getRepository(Timestamps::class);
            $timestamps = $repository->findOneBy([
                'entityId' => $entity->getId(),
                'entityClass' => get_class($entity),
            ]);
            if ($timestamps) {
                $timestamps->setUpdatedAt(new \DateTimeImmutable());
                $entityManager->flush();
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof AbstractLatestTimestamp) {
            $entityManager = $args->getObjectManager();
            $repository = $entityManager->getRepository(Timestamps::class);
            $timestamps = $repository->findBy([
                'entityId' => $entity->getId(),
                'entityClass' => get_class($entity),
            ]);
            foreach ($timestamps as $timestamp) {
                $entityManager->remove($timestamp);
            }

            $entityManager->flush();
        }
    }

}
