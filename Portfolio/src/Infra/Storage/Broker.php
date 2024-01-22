<?php

namespace App\Infra\Storage;

use App\Infra\Storage\Account\Custodian;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
class Broker extends Custodian
{
    public function __construct(Uuid $id, string $name)
    {
        parent::__construct(id: $id, name: $name);
    }

    public function getCustodian(): string
    {
        // TODO: Implement getCustodian() method.
    }
}
