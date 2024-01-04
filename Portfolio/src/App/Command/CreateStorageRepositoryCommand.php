<?php

declare(strict_types=1);

namespace App\App\Command;

use App\Infra\Storage\Storage;

final class CreateStorageRepositoryCommand
{
    private Storage $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function getStorageRepository(): Storage
    {
        return $this->storage;
    }
}
