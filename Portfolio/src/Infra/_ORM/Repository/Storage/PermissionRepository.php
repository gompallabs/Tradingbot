<?php

declare(strict_types=1);

namespace App\Infra\_ORM\Repository\Storage;

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

    public function getUserAccount(User $user, StorageInterface $bddStorage)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select(
                'p.id',
                'p.permissions',
                'u.username',
                'a.id',
                'a.type as accountType',
                's.name AS storageName'
            )
            ->from(Permission::class, 'p')
            ->leftJoin(join: Account::class, alias:'a', conditionType: Expr\Join::WITH, condition: 'p.account=a' )
            ->leftJoin(join: User::class, alias: 'u', conditionType: Expr\Join::WITH, condition: 'u=p.user')
            ->leftJoin(join: Storage::class, alias: 's', conditionType: Expr\Join::WITH, condition:  's=a.storage')
            ->where('u.id=:uid')
            ->setParameter('uid', $user->getId())
            ->andWhere('s.id=:sid')
            ->setParameter('sid', $bddStorage->getId())
        ;
        return $qb->getQuery()->getResult();
    }
}