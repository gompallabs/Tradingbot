<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Infra\Storage\Account\Permission;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class PermissionFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $john = $this->getReference('john');
        $johnBybitAccount = $this->getReference('john-bybit');
        $johnBitgetAccount = $this->getReference('john-bitget');

        $permissionOnBybitAccount = new Permission(id: Uuid::v4(), user: $john, account: $johnBybitAccount);
        $permissionOnBybitAccount->addPermission('r');
        $permissionOnBybitAccount->addPermission('w');
        $manager->persist($permissionOnBybitAccount);
        $this->addReference('permission-john-bybit', $permissionOnBybitAccount);

        $permissionOnBitgetAccount = new Permission(id: Uuid::v4(), user: $john, account: $johnBitgetAccount);
        $permissionOnBitgetAccount->addPermission('r');
        $permissionOnBitgetAccount->addPermission('w');
        $manager->persist($permissionOnBitgetAccount);
        $this->addReference('permission-john-bitget', $permissionOnBitgetAccount);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AccountFixtures::class,
        ];
    }

    public static function getGroups(): array
    {
        return ['init', 'manager'];
    }
}
