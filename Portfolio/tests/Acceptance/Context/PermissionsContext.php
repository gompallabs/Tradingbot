<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Domain\User\Event\Delegation;
use App\Domain\User\Event\DelegationCancel;
use App\Domain\User\Orm\Doctrine\SquadRepositoryInterface;
use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use App\Infra\Storage\Account\EventHandler\DelegationCancelEventHandler;
use App\Infra\Storage\Account\EventHandler\DelegationEventHandler;
use App\Infra\Storage\Account\Permission;
use App\Infra\User\User;
use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

class PermissionsContext implements Context
{
    private AccountRepositoryInterface $accountRepository;
    private UserRepositoryInterface $userRepository;
    private ?Delegation $delegation = null;
    private ?DelegationCancel $delegationCancel = null;
    private MessageBusInterface $eventBus;
    private KernelInterface $kernel;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        SquadRepositoryInterface $squadRepository,
        StorageRepositoryInterface $storageRepository,
        UserRepositoryInterface $userRepository,
        MessageBusInterface $eventBus,
        KernelInterface $kernel
    ) {
        $this->accountRepository = $accountRepository;
        $this->squadRepository = $squadRepository;
        $this->storageRepository = $storageRepository;
        $this->userRepository = $userRepository;
        $this->eventBus = $eventBus;
        $this->kernel = $kernel;
    }

    /**
     * @Given I create an account management delegation from :arg1 to :arg2 on :arg3
     */
    public function iCreateAnAccountManagementDelegationFromToOn($arg1, $arg2, $arg3)
    {
        $user = $this->userRepository->findOneBy(['username' => $arg1]);
        assertInstanceOf(User::class, $user);

        $manager = $this->userRepository->findOneBy(['username' => $arg2]);
        assertInstanceOf(User::class, $user);

        $userPermissions = $user->getPermissions();
        $permissionArray = array_filter($userPermissions->toArray(), function (Permission $permission) use ($arg3) {
            return $permission->getAccount()->getStorageName() === $arg3;
        });
        $permission = array_pop($permissionArray);
        $delegation = new Delegation(
            userId: $user->getId(),
            managerId: $manager->getId(),
            accountId: $permission->getAccount()->getId()
        );
        assertInstanceOf(Delegation::class, $delegation);
        $this->delegation = $delegation;
    }

    /**
     * @Given I dispatch the delegation event
     */
    public function iDispatchTheDelegationEvent()
    {
        assertInstanceOf(Delegation::class, $this->delegation);
        $this->eventBus->dispatch($this->delegation);
    }


    /**
     * @Given I dispatch the delegationCancellation event
     */
    public function iDispatchTheDelegationcancellationEvent()
    {
        $delegation = $this->delegation;
        $delegationCancel = new DelegationCancel(
            userId: $delegation->getUserId(),
            managerId: $delegation->getManagerId(),
            accountId: $delegation->getAccountId()
        );
        $this->eventBus->dispatch($delegationCancel);
    }


    /**
     * @Given I should have :arg1 message in the transport containing a :arg2 event
     */
    public function iShouldHaveMessageInTheTransportContainingAEvent($arg1, $arg2)
    {
        $testContainer = $this->kernel->getContainer()->get('test.service_container');
        $transport = $testContainer->get('messenger.transport.sync');

        $queueContent = $transport->get();
        $envelope = $queueContent[0];
        $message = $envelope->getMessage();
        $transport->ack($envelope);

        if ($arg2 === 'delegation') {
            assertInstanceOf(Delegation::class, $message);
            $this->delegation = $message;
        } elseif ($arg2 === 'delegationCancel'){
            assertInstanceOf(DelegationCancel::class, $message);
            $this->delegationCancel = $message;
        }
        else {
            assertTrue(false);
        }
    }

    /**
     * @Given I should handle the :arg1 event
     */
    public function iShouldHandleTheEvent($arg1)
    {
        if($arg1 === 'delegation'){
            $handler = new DelegationEventHandler(
                accountRepository: $this->accountRepository,
                userRepository: $this->userRepository
            );
            $handler($this->delegation);
        } elseif ($arg1 === 'delegationCancel'){
            $handler = new DelegationCancelEventHandler(
                accountRepository: $this->accountRepository,
                userRepository: $this->userRepository
            );
            $handler($this->delegationCancel);
        }

    }

    /**
     * @Then the user :arg1 should have permissions :arg2 and :arg3 on :arg4
     */
    public function theUserShouldHavePermissionsAndOn($arg1, $arg2, $arg3, $arg4)
    {
        $accountId = $this->delegation->getAccountId();
        $userId = $this->delegation->getUserId();
        $userPermission = $this->accountRepository->getPermissionForUser(accountId: $accountId, userId: $userId);
        $values = $userPermission->getPermissions();
        assertEquals($values, [
            'r','w'
        ]);
    }

    /**
     * @Then the user :arg1 should have permissions :arg2 and :arg3 and :arg4 on :arg5
     */
    public function theUserShouldHavePermissionsAndAndOn($arg1, $arg2, $arg3, $arg4, $arg5)
    {
        $accountId = $this->delegation->getAccountId();
        $userId = $this->delegation->getUserId();
        $userPermission = $this->accountRepository->getPermissionForUser(accountId: $accountId, userId: $userId);
        $values = $userPermission->getPermissions();
        sort($values);
        assertEquals($values, [
            'r','t', 'w'
        ]);
    }


    /**
     * @Then the manager :arg1 should have permissions :arg2 and :arg3 on :arg4
     */
    public function theManagerShouldHavePermissionsAndOn($arg1, $arg2, $arg3, $arg4)
    {
        $accountId = $this->delegation->getAccountId();
        $managerId = $this->delegation->getManagerId();
        $managerPermission = $this->accountRepository->getPermissionForUser(accountId: $accountId, userId: $managerId);
        $values = $managerPermission->getPermissions();
        assertEquals($values, [
            'r','t'
        ]);
    }

    /**
     * @Then the manager :arg1 should have no permissions :arg2
     */
    public function theManagerShouldHaveNoPermissions($arg1, $arg2)
    {
        $accountId = $this->delegation->getAccountId();
        $managerId = $this->delegation->getManagerId();
        $managerPermission = $this->accountRepository->getPermissionForUser(accountId: $accountId, userId: $managerId);
        assertNull($managerPermission);
    }
}
