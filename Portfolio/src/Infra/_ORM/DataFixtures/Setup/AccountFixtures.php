<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Setup;

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
        $bybit = $this->getReference('bybit');
        $bitget = $this->getReference('bitget');
        $bitpanda = $this->getReference('bitpanda');

        // we create the fiat accounts for toto
        $bybitFiat = new Account(Uuid::v4(), AccountType::FIAT);
        $bybitFiat->setStorage($bybit);
        $this->addReference('toto-bybit-fiat', $bybitFiat);
        $manager->persist($bybitFiat);

        $bitgetFiat = new Account(Uuid::v4(), AccountType::FIAT);
        $bitgetFiat->setStorage($bitget);
        $this->addReference('toto-bitget-fiat', $bitgetFiat);
        $manager->persist($bitgetFiat);

        $bitpandaFiat = new Account(Uuid::v4(), AccountType::FIAT);
        $bitpandaFiat->setStorage($bitpanda);
        $this->addReference('toto-bitpanda-fiat', $bitpandaFiat);
        $manager->persist($bitpandaFiat);

        // we create the spot accounts for toto
        $bybitSpot = new Account(Uuid::v4(), AccountType::ASSET_SPOT);
        $bybitSpot->setStorage($bybit);
        $this->addReference('toto-bybit-spot', $bybitSpot);
        $manager->persist($bybitSpot);

        $bitgetSpot = new Account(Uuid::v4(), AccountType::ASSET_SPOT);
        $bitgetSpot->setStorage($bitget);
        $this->addReference('toto-bitget-spot', $bybitSpot);
        $manager->persist($bitgetSpot);

        $bitpandaSpot = new Account(Uuid::v4(), AccountType::ASSET_SPOT);
        $bitpandaSpot->setStorage($bitpanda);
        $this->addReference('toto-bitpanda-spot', $bitpandaSpot);
        $manager->persist($bitpandaSpot);

        // we create the margin accounts for toto
        $bybitFutTrading = new Account(Uuid::v4(), AccountType::ASSET_FUTURES_TRADING);
        $bybitFutTrading->setStorage($bybit);
        $this->addReference('toto-bybit-trading', $bybitFutTrading);
        $manager->persist($bybitFutTrading);

        $bitgetFutTrading = new Account(Uuid::v4(), AccountType::ASSET_FUTURES_TRADING);
        $bitgetFutTrading->setStorage($bitget);
        $this->addReference('toto-bitget-trading', $bybitFutTrading);
        $manager->persist($bitgetFutTrading);

        $bitpandaFutTrading = new Account(Uuid::v4(), AccountType::ASSET_FUTURES_TRADING);
        $bitpandaFutTrading->setStorage($bitpanda);
        $this->addReference('toto-bitpanda-trading', $bitpandaFutTrading);
        $manager->persist($bitpandaFutTrading);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['setup'];
    }

    public function getDependencies()
    {
        return [
            StorageFixtures::class
        ];
    }
}