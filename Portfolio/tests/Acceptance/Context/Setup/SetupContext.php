<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context\Setup;

use App\Domain\Storage\Orm\Doctrine\AccountRepositoryInterface;
use App\Domain\Storage\Orm\Doctrine\AccountType;
use App\Domain\Storage\Orm\Doctrine\PermissionRepositoryInterface;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Domain\User\Orm\Doctrine\UserRepositoryInterface;
use App\Infra\Storage\Broker;
use App\Infra\Storage\CryptoExchange;
use App\Infra\Storage\Storage;
use App\Infra\User\User;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use http\Exception\RuntimeException;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;

class SetupContext implements Context
{
    private AccountRepositoryInterface $accountRepository;

    private PermissionRepositoryInterface $permissionRepository;
    private StorageRepositoryInterface $storageRepository;
    private UserRepositoryInterface $userRepository;

    public function __construct(
        AccountRepositoryInterface $accountRepository,
        PermissionRepositoryInterface $permissionRepository,
        StorageRepositoryInterface $storageRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->accountRepository = $accountRepository;
        $this->permissionRepository = $permissionRepository;
        $this->storageRepository = $storageRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Then I should have the user :arg1
     */
    public function iShouldHaveTheUser($arg1)
    {
        $user = $this->userRepository->findOneBy(['username' => $arg1]);
        assertInstanceOf(User::class, $user);
    }

    /**
     * @Then the following :arg1 storages should exist:
     */
    public function theFollowingStoragesShouldExist($arg1, TableNode $table)
    {
        $list = explode(",", $table->getColumn(0)[1]);
        foreach ($list as $storage){
            $storageName = strtolower(trim($storage));
            $bddExchange = $this->storageRepository->findOneBy(['name' => $storageName]);
            if($arg1 === 'cryptoExchange'){
                assertInstanceOf(CryptoExchange::class, $bddExchange);
            } elseif ($arg1 === 'brokerage'){
                assertInstanceOf(Broker::class, $bddExchange);
            }
        }
    }

    /**
     * @Then user :arg1 should have for each :arg2 storage a :arg3 account:
     */
    public function userShouldHaveForEachStorageAAccount($arg1, $arg2, $arg3, TableNode $table)
    {
        $username = $arg1;
        $storageType = $arg2;
        $accountType = trim(strtoupper($arg3));

        $user = $this->userRepository->findOneBy(['username' => $username]);
        $listOfStorage = explode(",", $table->getColumn(0)[1]);
        foreach ($listOfStorage as $storage){
            $bddStorage = $this->storageRepository->findOneBy(['name' => strtolower(trim($storage))]);
            if($storageType === 'cryptoExchange'){
                assertInstanceOf(CryptoExchange::class, $bddStorage);
            }

            if(AccountType::tryFrom($accountType) === null){
                throw new RuntimeException('chose a valid account type for this test !');
            }

            // request permissions where account has $bddStorage and $user === $username
            $queryResult = $this->permissionRepository->getUserAccount($user, $bddStorage, AccountType::tryFrom($accountType));
            assertCount(1, $queryResult);
            assertEquals(AccountType::tryFrom($accountType), $queryResult[0]['accountType']);
        }
    }
}