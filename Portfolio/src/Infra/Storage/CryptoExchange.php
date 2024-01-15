<?php

declare(strict_types=1);

namespace App\Infra\Storage;

use App\Domain\Storage\CustodialStorage;
use App\Infra\Storage\Account\Custodian;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class CryptoExchange extends Custodian implements CustodialStorage
{
    public function __construct(Uuid $id, string $name)
    {
        parent::__construct($id, $name);
    }

    public function hasRestApi(): bool
    {
        return true;
    }

    public function hasWsApi(): bool
    {
        return true;
    }
}
