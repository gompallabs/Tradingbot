<?php

namespace App\Infra\Asset;

use App\Domain\Asset\AssetCategory;
use App\Domain\Asset\AssetInterface;
use App\Infra\Storage\Account\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class Asset implements AssetInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'NONE')]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column()]
    private string $name;

    #[ORM\Column()]
    private string $ticker;

    #[ORM\Column(type: 'string', enumType: AssetCategory::class)]
    private AssetCategory $assetCategory;

    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Transaction::class)]
    #[ORM\JoinColumn(name: 'transaction_id', referencedColumnName: 'id')]
    private Collection $transactions;

    public function __construct(Uuid $id, string $name, string $ticker)
    {
        $this->id = $id;
        $this->name = $name;
        $this->ticker = $ticker;
        $this->transactions = new ArrayCollection();
    }

    public function setAssetCategory(AssetCategory $assetCategory): void
    {
        $this->assetCategory = $assetCategory;
    }

    public function getAssetCategory(): AssetCategory
    {
        return $this->assetCategory;
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
