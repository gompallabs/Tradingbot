<?php

declare(strict_types=1);

namespace App\Infra\Storage\Account;

use App\Domain\Storage\CustodialStorage;
use App\Infra\Storage\Storage;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/*
 * An AccountHolder has to open a custodial account in order to trade securities
 */
#[ORM\MappedSuperclass()]
class Custodian extends Storage
{
    public function __construct(Uuid $id, string $name)
    {
        parent::__construct($id, $name);
    }

    public function getCustodian(): string
    {
        // TODO: Implement getCustodian() method.
    }
}
