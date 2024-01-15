<?php

namespace App\Infra\_ORM\Repository\User;

use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use App\Infra\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
