<?php

namespace App\Domain\Storage;

interface HotStorage extends Storage
{
    public function hasRestApi(): bool;

    public function hasWsApi(): bool;
}
