<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use App\Domain\Storage\ColdStorage;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class ColdWallet extends Storage implements ColdStorage
{
    protected bool $hot = false;

    public function __construct(string $name)
    {
        parent::__construct($name);
    }
}
