<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account\EventHandler;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Domain\User\Event\DelegationCancel;
use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DelegationCancelEventHandler
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

    public function __invoke(DelegationCancel $delegationCancel): void
    {
        $user = $this->userRepository->find($delegationCancel->getUserId());
        $manager = $this->userRepository->find($delegationCancel->getManagerId());
        $account = $this->accountRepository->find($delegationCancel->getAccountId());

        // remove the permissions of manager
        $account = $this->accountRepository->find($account->getId());
        $managerPermission = $this->accountRepository->getPermissionForUser(
            accountId: $account->getId(),
            userId: $manager->getId()
        );
        if($managerPermission !== null){
            $account->removePermission($managerPermission);
            $this->accountRepository->save($account);
            $account = $this->accountRepository->find($account->getId());
        }

        // add 't' permission to user
        $userPermission = $this->accountRepository->getPermissionForUser(
            accountId: $account->getId(),
            userId: $user->getId()
        );
        $userPermission->addPermission('t');
        $this->accountRepository->save($account);
    }
}