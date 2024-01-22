<?php

namespace App\Infra\Asset\Category\Security;

use App\Domain\Asset\AssetCategory;
use App\Infra\Asset\Asset;
use Doctrine\Common\Collections\Collection;

class Equity extends Asset
{
    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Security;
    }

    public function getTransactions(): Collection
    {
        // TODO: Implement getTransactions() method.
    }
}
