<?php

declare(strict_types=1);

namespace App\Domain\Source;

use App\Domain\Source\Api\SourceApiType;
use App\Domain\Source\Api\SourceFileType;

enum SourceType: string
{
    case Api = SourceApiType::class;
    case File = SourceFileType::class;
}
