<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'storage' => Storage::class,
    'brokerageAccount' => BrokerageAccount::class,
    'cryptoExchange' => CryptoExchange::class,
    'coldWallet' => ColdWallet::class,
])]
abstract class Storage
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'boolean')]
    protected bool $hot = true;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\Column(type: 'string')]
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
