<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use App\Domain\Storage\Storage as StorageInterface;
use App\Infra\Storage\Account\Account;
use App\Infra\User\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'storage' => Storage::class,
    'brokerageAccount' => Broker::class,
    'cryptoExchange' => CryptoExchange::class,
    'sovereignWallet' => SovereignWallet::class,
])]
abstract class Storage implements StorageInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'boolean')]
    protected bool $hot = true;

    #[ORM\Column(type: 'string', unique: true)]
    protected string $name;

    #[ORM\OneToMany(mappedBy: 'storage', targetEntity: Account::class)]
    protected Collection $account;

    public function __construct(Uuid $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAccount()
    {
        return $this->account;
    }
}
