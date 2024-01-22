<?php

declare(strict_types=1);

namespace App\Domain\Asset;

/*
 * Here Category design macro concept of Security or Commodity
 */
enum AssetCategory: string
{
    case Null = 'null';
    case Security = 'Security';
    case Commodity = 'Commodity';
}
