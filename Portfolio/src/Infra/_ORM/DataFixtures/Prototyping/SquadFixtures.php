<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Infra\User\Squad;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class SquadFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $squad = new Squad(id: Uuid::v4(), name: 'manager');
        $manager->persist($squad);
        $manager->flush();
        $this->addReference(name: 'manager-squad', object: $squad);
    }

    public static function getGroups(): array
    {
        return ['init', 'manager'];
    }
}
