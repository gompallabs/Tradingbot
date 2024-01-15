<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Domain\User\Orm\Doctrine\SquadRepositoryInterface;
use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use App\Infra\Storage\Account\Permission;
use App\Infra\Storage\CryptoExchange;
use App\Infra\User\Squad;
use Behat\Behat\Context\Context;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertTrue;

class UniverseContext implements Context
{
    private AccountRepositoryInterface $accountRepository;
    private SquadRepositoryInterface $squadRepository;
    private StorageRepositoryInterface $storageRepository;
    private UserRepositoryInterface $userRepository;
    private array $permissions = [];

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        SquadRepositoryInterface $squadRepository,
        StorageRepositoryInterface $storageRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->squadRepository = $squadRepository;
        $this->storageRepository = $storageRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Then I should have :arg1 owner
     */
    public function iShouldHaveOwner($arg1)
    {
        $users = $this->userRepository->findAll();
        assertCount(1, $users);
    }
    /**
     * @Then the user :arg1 should have both :arg2 and :arg3 permissions on :arg4 accounts
     */
    public function theUserShouldHaveBothAndPermissionsOnAccounts($arg1, $arg2, $arg3, $arg4)
    {
        $this->permissions = [];
        $username = $arg1;
        $accounts = $this->accountRepository->findAll();
        $userPermissionCount = 0;
        foreach ($accounts as $account){
            $permissions = $account->getPermissions();
            $userPermissions = array_filter($permissions->toArray(), function (Permission $permission) use ($username) {
                return
                    $permission->getUser()->getUsername() === $username
                    && in_array('r', $permission->getPermissions())
                    && in_array('w', $permission->getPermissions())
                    ;
            });
            if(count($userPermissions) > 0){
                $this->permissions[] = array_shift($userPermissions);
                $userPermissionCount++;
            }

        }
        assertEquals(2, $userPermissionCount);
    }

    /**
     * @Then there should be one :arg1 account
     */
    public function thereShouldBeOneAccount($arg1)
    {
        $permissions = $this->permissions;
        assertCount(1, array_filter($permissions, function (Permission $permission) use ($arg1) {
            return $permission->getAccount()->getStorageName() === $arg1;
        }));
    }

    /**
     * @Then I should have more than :arg1 cryptoExchange
     */
    public function iShouldHaveMoreThanCryptoexchange($arg1)
    {
        $countCryptoExchanges = 0;
        $exch = $this->storageRepository->findAll();
        foreach ($exch as $raw) {
            if ($raw instanceof CryptoExchange) {
                ++$countCryptoExchanges;
            }
        }
        assertGreaterThan($arg1, $countCryptoExchanges);
    }

    /**
     * @Given I should have a :arg1 squad
     */
    public function iShouldHaveASquad($arg1)
    {
        $groups = $this->squadRepository->findAll();
        $managerSquad = array_filter($groups, function (Squad $group) use ($arg1) {
            return $group->getName() === $arg1;
        });
        assertCount(1, $managerSquad);
    }

    /**
     * @Then I should have :arg2 owner with no squad and :arg3 owner member of the :arg1 squad
     */
    public function iShouldHaveOwnerWithNoSquadAndOwnerMemberOfTheSquad($arg1, $arg2, $arg3)
    {
        $users = $this->userRepository->findAll();
        $squadMember = [];
        $nonSquadMember = [];
        foreach ($users as $user) {
            if ($user->getSquads()->count() > 0) {
                foreach ($user->getSquads() as $squad) {
                    if ($squad->getName() === $arg1) {
                        $squadMember[] = $user;
                    }
                }
            } else {
                $nonSquadMember[] = $user;
            }
        }
        assertCount(1, $squadMember);
        assertCount(1, $nonSquadMember);
    }
}
