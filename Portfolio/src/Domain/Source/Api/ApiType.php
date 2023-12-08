<?php

declare(strict_types=1);

namespace App\Domain\Source\Api;

enum ApiType: string
{
    case Rest = "Rest";
}