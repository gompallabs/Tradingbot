<?php

namespace App\Domain\Storage\Orm\Doctrine;

use App\Infra\Storage\Account\Account;
use App\Infra\Storage\Account\Permission;
use Symfony\Component\Uid\Uuid;

interface AccountRepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function save(Account $account);

    public function getPermissionForUser(Uuid $accountId, Uuid $userId): ?Permission;
}
