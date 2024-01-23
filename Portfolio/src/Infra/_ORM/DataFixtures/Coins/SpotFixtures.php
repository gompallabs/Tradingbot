<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Coins;

use App\Domain\Asset\Category\Commodity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class SpotFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $bitcoin = new Commodity(
            id: Uuid::v4(),
            name: 'bitcoin',
            ticker: 'BTCUSD'
        );
        $manager->persist($bitcoin);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return [
            "coins"
        ];
    }
}