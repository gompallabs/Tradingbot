<?php

namespace App\Infra\Asset;

use App\Domain\Asset\AssetCategory;
use App\Domain\Asset\AssetInterface;
use App\Infra\Asset\Category\Commodity\Commodity;
use App\Infra\Asset\Category\Security\Crypto;
use App\Infra\Asset\Category\Security\Equity;
use App\Infra\Storage\Account\Transaction;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorColumn(name: 'category', type: 'string')]
#[ORM\DiscriminatorMap([
    'Commodity' => Commodity::class,
    'Crypto' => Crypto::class,
    'Equity' => Equity::class,
])]
class Asset implements AssetInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'string')]
    protected string $name;

    #[ORM\Column(type: 'string')]
    protected string $ticker;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Transaction::class)]
    protected Collection $transactions;

    public function __construct(Uuid $id, string $name, string $ticker)
    {
        $this->id = $id;
        $this->name = $name;
        $this->ticker = $ticker;
    }

    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Null;
    }

    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTicker(): string
    {
        return $this->ticker;
    }
}
