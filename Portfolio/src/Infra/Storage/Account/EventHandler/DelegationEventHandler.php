<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account\EventHandler;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Domain\User\Event\Delegation;
use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use App\Infra\Storage\Account\Permission;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler]
class DelegationEventHandler
{
    private AccountRepositoryInterface $accountRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Delegation $delegation): void
    {
        $user = $this->userRepository->find($delegation->getUserId());
        $manager = $this->userRepository->find($delegation->getManagerId());

        // replace user permission with "r" and "w" permissions on account
        $userId = $user->getId();
        $account = $this->accountRepository->find($delegation->getAccountId());
        $userPermission = $this->accountRepository->getPermissionForUser(
            accountId: $account->getId(),
            userId: $userId
        );
        $account->removePermission($userPermission);
        $this->accountRepository->save($account);

        $account = $this->accountRepository->find($account->getId());
        $userPermission = new Permission(Uuid::v4(), $user, $account);
        $userPermission->addPermission('r');
        $userPermission->addPermission('w');
        $account->addPermission($userPermission);
        $this->accountRepository->save($account);

        // replace manager permission with "r" and "t" permissions on account
        $managerId = $manager->getId();
        $account = $this->accountRepository->find($account->getId());
        $managerPermission = $this->accountRepository->getPermissionForUser(
            accountId: $account->getId(),
            userId: $managerId
        );
        if($managerPermission !== null){
            $account->removePermission($managerPermission);
            $this->accountRepository->save($account);
            $account = $this->accountRepository->find($account->getId());
        }
        $managerPermission = new Permission(Uuid::v4(), $manager, $account);
        $managerPermission->addPermission('r');
        $managerPermission->addPermission('t');
        $account->addPermission($managerPermission);
        $this->accountRepository->save($account);
    }
}
