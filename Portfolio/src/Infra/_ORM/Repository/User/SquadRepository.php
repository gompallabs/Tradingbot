<?php

declare(strict_types=1);

namespace App\Infra\_ORM\Repository\User;

use App\Domain\User\Orm\Doctrine\SquadRepositoryInterface;
use App\Domain\User\OwnerInterface;
use App\Infra\User\Squad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SquadRepository extends ServiceEntityRepository implements SquadRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Squad::class);
    }

    public function getSquadsOfUser(OwnerInterface $user)
    {
        // TODO: Implement getSquadsOfUser() method.
    }
}
