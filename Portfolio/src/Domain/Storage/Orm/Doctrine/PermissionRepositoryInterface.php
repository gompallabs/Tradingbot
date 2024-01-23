<?php

namespace App\Domain\Storage\Orm\Doctrine;

use App\Domain\Storage\Storage;
use App\Infra\User\User;

interface PermissionRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);
    public function findOneBy(array $criteria, ?array $orderBy = null);
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null);
    public function findAll();
    public function getUserAccount(User $user, Storage $bddStorage, ?AccountType $accountType = null);
}