<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Infra\Storage\CryptoExchange;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class StorageFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $bitget = new CryptoExchange(Uuid::v4(), 'bitget');
        $this->addReference('bitget-storage', $bitget);
        $manager->persist($bitget);

        $bybit = new CryptoExchange(Uuid::v4(), 'bybit');
        $this->addReference('bybit-storage', $bybit);
        $manager->persist($bybit);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'manager'];
    }
}
