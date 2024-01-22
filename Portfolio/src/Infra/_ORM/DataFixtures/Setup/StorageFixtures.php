<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Setup;

use App\Infra\Storage\Broker;
use App\Infra\Storage\CryptoExchange;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class StorageFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        //|binance|
        $binance = new CryptoExchange(id: Uuid::v4(), name: 'binance');
        $manager->persist($binance);
        $this->addReference('binance', $binance);

        //|bybit|
        $bybit = new CryptoExchange(id: Uuid::v4(), name: 'bybit');
        $manager->persist($bybit);
        $this->addReference('bybit', $bybit);

        //|bitget|
        $bitget = new CryptoExchange(id: Uuid::v4(), name: 'bitget');
        $manager->persist($bitget);
        $this->addReference('bitget', $bitget);

        //|kraken|
        $kraken = new CryptoExchange(id: Uuid::v4(), name: 'kraken');
        $manager->persist($kraken);
        $this->addReference('kraken', $kraken);

        //|woox|
        $woox = new CryptoExchange(id: Uuid::v4(), name: 'woox');
        $manager->persist($woox);
        $this->addReference('woox', $woox);

        //|bitpanda|
        $bitpanda = new CryptoExchange(id: Uuid::v4(), name: 'bitpanda');
        $manager->persist($bitpanda);
        $this->addReference('bitpanda', $bitpanda);

        //|mexc|
        $mexc = new CryptoExchange(id: Uuid::v4(), name: 'mexc');
        $manager->persist($mexc);
        $this->addReference('mexc', $mexc);

        //|bithumb|
        $bithumb = new CryptoExchange(id: Uuid::v4(), name: 'bithumb');
        $manager->persist($bithumb);
        $this->addReference('bithumb', $bithumb);

        //|saxo|
        $saxo = new Broker(id: Uuid::v4(), name: 'saxo');
        $manager->persist($saxo);
        $this->addReference('saxo', $saxo);

        //|ibkr|
        $ibkr = new Broker(id: Uuid::v4(), name: 'ibkr');
        $manager->persist($ibkr);
        $this->addReference('ibkr', $ibkr);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'setup'
        ];
    }
}