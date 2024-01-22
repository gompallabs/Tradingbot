<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use App\Infra\Asset\Asset;

class Holding
{
    private float $quantity;
    private Asset $asset;
}
