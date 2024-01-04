<?php

namespace App\Infra\Storage;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
class BrokerageAccount extends Custodian
{
    public function __construct(string $name)
    {
        parent::__construct($name);
    }
}
