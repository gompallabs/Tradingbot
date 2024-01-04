<?php

namespace App\Domain\Asset;

interface Asset
{
    public function getAssetCategory(): AssetCategory;

    public function getAssetClass(): AssetClass;
}
