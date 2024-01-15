<?php

declare(strict_types=1);

namespace App\Infra\_ORM\DataFixtures\Setup;

use App\Infra\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $toto = new User(Uuid::v4(), 'toto@example.com');
        $manager->persist($toto);
        $this->addReference('toto', $toto);

        $titi = new User(Uuid::v4(), 'titi@example.com');
        $manager->persist($titi);
        $this->addReference('titi', $titi);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['setup'];
    }
}