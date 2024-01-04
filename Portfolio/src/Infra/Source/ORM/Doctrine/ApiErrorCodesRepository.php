<?php

declare(strict_types=1);

namespace App\Infra\Source\ORM\Doctrine;

use App\Domain\Source\Api\Orm\Doctrine\ApiErrorCodesRepositoryInterface;
use App\Infra\Source\ApiErrorCodes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ApiErrorCodesRepository extends ServiceEntityRepository implements ApiErrorCodesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiErrorCodes::class);
    }

    public function save(ApiErrorCodes $code): void
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository(ApiErrorCodes::class);
        $exists = $repo->findOneBy([
            'repository' => $code->getRepository(),
            'code' => $code->getCode(),
        ]);
        if ($exists instanceof ApiErrorCodes === false) {
            $em->persist($code);
            $em->flush();
        }
    }
}
