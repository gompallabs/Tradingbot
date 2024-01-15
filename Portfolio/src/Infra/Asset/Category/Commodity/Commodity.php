<?php

declare(strict_types=1);

namespace App\Infra\Asset\Category\Commodity;

use App\Domain\Asset\AssetCategory;
use App\Infra\Asset\Asset;
use App\Infra\Storage\Account\Transaction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

class Commodity extends Asset
{
    #[ORM\OneToMany(mappedBy: 'asset', targetEntity: Transaction::class)]
    protected Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Commodity;
    }

    public function getTransactions(): Collection
    {
        // TODO: Implement getTransactions() method.
    }
}
