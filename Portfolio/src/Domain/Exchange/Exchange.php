<?php

namespace App\Domain\Exchange;

use Doctrine\Common\Collections\Collection;

abstract class Exchange
{
    protected string $name;

    protected Collection $listedAssets;
}
