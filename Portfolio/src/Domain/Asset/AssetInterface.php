<?php

namespace App\Domain\Asset;

interface AssetInterface
{
    public function getAssetCategory(): AssetCategory;
    public function getTicker();
    public function getName();
}
