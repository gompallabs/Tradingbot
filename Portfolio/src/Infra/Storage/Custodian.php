<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use App\Domain\Storage\HotStorage;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass()]
class Custodian extends Storage implements HotStorage
{
    public function hasRestApi(): bool
    {
        return true;
    }

    public function hasWsApi(): bool
    {
        return true;
    }
}
