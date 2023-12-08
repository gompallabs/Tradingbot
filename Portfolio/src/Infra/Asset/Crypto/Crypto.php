<?php

declare(strict_types=1);

namespace App\Infra\Asset\Crypto;

use App\Domain\Asset\AssetCategory;
use App\Domain\Asset\AssetClass;

class Crypto extends AssetClass
{
    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Security;
    }
}