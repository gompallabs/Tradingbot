<?php

declare(strict_types=1);

namespace App\Infra\_ORM\Repository\Storage;

use App\Domain\Storage\Orm\Doctrine\AccountType;
use App\Domain\Storage\Orm\Doctrine\PermissionRepositoryInterface;
use App\Infra\Storage\Account\Account;
use App\Infra\Storage\Account\Permission;
use App\Domain\Storage\Storage as StorageInterface;
use App\Infra\Storage\Storage;
use App\Infra\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends ServiceEntityRepository implements PermissionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permission::class);
    }

    public function getUserAccount(User $user, StorageInterface $bddStorage, ?AccountType $accountType = null)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select(
                'p.id',
                'p.permissions',
                'a.id',
                'a.type as accountType',
                's.name AS storageName'
            )
            ->from(Permission::class, 'p')
            ->innerJoin(User::class, 'u', Expr\Join::WITH, 'u=p.user')
            ->where('u.id=:uid')
            ->setParameter('uid', $user->getId())
            ->innerJoin(Account::class, 'a', Expr\Join::WITH, 'p.account=a')
            ->innerJoin(Storage::class, 's', Expr\Join::WITH, 's=a.storage')
            ->andWhere('s.id=:sid')
            ->setParameter('sid', $bddStorage->getId());

        if($accountType !== null){
            $qb->andWhere('a.type=:atype')
                ->setParameter('atype',  $accountType->value)
            ;
        }

        return $qb->getQuery()->getResult();
    }
}