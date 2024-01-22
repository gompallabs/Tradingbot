<?php

declare(strict_types=1);

namespace App\Infra\_ORM\Repository\Storage;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Infra\Storage\Account\Account;
use App\Infra\Storage\Account\Permission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class AccountRepository extends ServiceEntityRepository implements AccountRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function save(Account $account)
    {
        $em = $this->getEntityManager();
        $em->persist($account);
        $em->flush();
    }

    public function getPermissionForUser(Uuid $accountId, Uuid $userId): ?Permission
    {
        $account = $this->find($accountId);
        $userPermissionArray = array_filter($account->getPermissions()->toArray(), function (Permission $permission) use ($userId) {
            return $userId === $permission->getUser()->getId();
        });

        return array_shift($userPermissionArray);
    }
}
