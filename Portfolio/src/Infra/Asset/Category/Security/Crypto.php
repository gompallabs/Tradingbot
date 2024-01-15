<?php

declare(strict_types=1);

namespace App\Infra\Asset\Category\Security;

use App\Domain\Asset\AssetCategory;
use App\Domain\Asset\TokenizedInterface;

abstract class Crypto extends Equity implements TokenizedInterface
{
    public function getAssetCategory(): AssetCategory
    {
        return AssetCategory::Security;
    }
}
