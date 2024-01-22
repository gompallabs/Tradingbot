<?php

declare(strict_types=1);

namespace App\Infra\_ORM\Repository\Storage;

use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Infra\Storage\Account\Custodian;
use App\Infra\Storage\Storage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class StorageRepository extends ServiceEntityRepository implements StorageRepositoryInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Storage::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Storage
    {
        $repository = $this->getEntityManager()->getRepository(Storage::class);
        if (array_key_exists('name', $criteria)) {
            return $repository->findOneByName($criteria['name']);
        }

        return null;
    }

    public function save(Storage $custodian): void
    {
        $em = $this->getEntityManager();
        $repository = $em->getRepository(Custodian::class);
        $exists = $repository->findOneBy(['name' => $custodian->getName()]);
        if ($exists instanceof Custodian === false) {
            $em->persist($custodian);
            $em->flush();
        }
    }
}
