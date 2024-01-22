<?php

namespace App\Infra\Asset\Assets;

use App\Domain\Asset\TokenizedInterface;
use App\Infra\Asset\Category\Commodity\Commodity;

class Bitcoin extends Commodity implements TokenizedInterface
{
    public function getName(): string
    {
        return 'bitcoin';
    }
}
