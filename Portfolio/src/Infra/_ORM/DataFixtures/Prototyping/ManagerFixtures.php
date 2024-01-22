<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Infra\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ManagerFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new User(Uuid::v4(), 'janet.doe@example.com');
        $manager->persist($user);
        $this->addReference('janet', $user);
        $managerSquad = $this->getReference('manager-squad');
        $user->addSquad($managerSquad);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SquadFixtures::class,
        ];
    }


    public static function getGroups(): array
    {
        return ['manager'];
    }
}
