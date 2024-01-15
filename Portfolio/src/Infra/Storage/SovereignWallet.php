<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use App\Domain\Storage\SovereignStorage;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class SovereignWallet extends Storage implements SovereignStorage
{
    protected bool $hot = false;

    public function __construct(Uuid $id, string $name)
    {
        parent::__construct(id: $id, name: $name);
    }
}
