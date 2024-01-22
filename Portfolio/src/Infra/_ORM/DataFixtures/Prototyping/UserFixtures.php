<?php

namespace App\Infra\_ORM\DataFixtures\Prototyping;

use App\Infra\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = new User(Uuid::v4(), 'john.doe@example.com');
        $manager->persist($user);
        $this->addReference('john', $user);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['init', 'manager'];
    }
}
