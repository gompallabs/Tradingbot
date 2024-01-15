<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Domain\Storage\Orm\Doctrine\AccountType;
use App\Infra\Storage\Account\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class AccountFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $bybitAccount = new Account(id: Uuid::v4(), type: AccountType::ASSET_FUTURES_TRADING);
        $bybitStorage = $this->getReference('bybit-storage');
        $bybitAccount->setStorage($bybitStorage);
        $manager->persist($bybitAccount);
        $this->addReference('john-bybit', $bybitAccount);

        $bitgetAccount = new Account(id: Uuid::v4(), type: AccountType::ASSET_SPOT);
        $bitgetStorage = $this->getReference('bitget-storage');
        $bitgetAccount->setStorage($bitgetStorage);
        $manager->persist($bitgetAccount);
        $this->addReference('john-bitget', $bitgetAccount);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StorageFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['init', 'manager'];
    }
}
