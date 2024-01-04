<?php

declare(strict_types=1);

namespace App\Domain\Source\Api;

enum SourceApiType: string
{
    case Rest = 'Rest';
}
