<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class CryptoExchange extends Custodian
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }
}
