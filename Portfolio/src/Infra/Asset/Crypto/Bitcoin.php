<?php

declare(strict_types=1);

namespace App\Infra\Asset\Crypto;

use App\Domain\Asset\Asset;
use App\Domain\Asset\AssetCategory;
use App\Domain\Asset\AssetClass;

final class Bitcoin extends Crypto implements Asset
{
    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Commodity;
    }

    public function getAssetClass(): AssetClass
    {

    }
}