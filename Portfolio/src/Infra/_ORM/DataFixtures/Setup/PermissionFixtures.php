<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Setup;

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
        // toto FIAT accounts
        $toto = $this->getReference('toto');
        $totoBybitFiat = $this->getReference('toto-bybit-fiat');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBybitFiat
        );
        $permission->setAccount($totoBybitFiat);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBybitFiat->addPermission($permission);
        $manager->persist($permission);


        $totoBitgetFiat = $this->getReference('toto-bitget-fiat');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBitgetFiat
        );
        $permission->setAccount($totoBitgetFiat);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBitgetFiat->addPermission($permission);
        $manager->persist($permission);


        $totoBitpandaFiat = $this->getReference('toto-bitpanda-fiat');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBitpandaFiat
        );
        $permission->setAccount($totoBitpandaFiat);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBitpandaFiat->addPermission($permission);
        $manager->persist($permission);

        // toto SPOT accounts
        $totoBybitSpot = $this->getReference('toto-bybit-spot');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBybitSpot
        );
        $permission->setAccount($totoBybitSpot);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBybitSpot->addPermission($permission);
        $manager->persist($permission);


        $totoBitgetSpot = $this->getReference('toto-bitget-spot');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBitgetSpot
        );
        $permission->setAccount($totoBitgetSpot);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBitgetSpot->addPermission($permission);
        $manager->persist($permission);


        $totoBitpandaSpot = $this->getReference('toto-bitpanda-spot');
        $permission = new Permission(
            id: Uuid::v4(),
            user: $toto,
            account: $totoBitpandaSpot
        );
        $permission->setAccount($totoBitpandaSpot);
        $permission->addPermission('o');
        $permission->addPermission('r');
        $permission->addPermission('w');
        $permission->addPermission('t');
        $totoBitpandaSpot->addPermission($permission);
        $manager->persist($permission);


        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            'setup'
        ];
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AccountFixtures::class
        ];
    }
}