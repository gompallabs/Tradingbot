<?php

declare(strict_types=1);

namespace App\Infra\Portfolio\Account;

use App\Domain\Portfolio\Account\Account as AccountInterface;

/*
 * An account is just an envelope to hold assets and to reflect transations
 * One mandatory asset is cash, in one or multiple currencies
 */
class Account implements AccountInterface
{
    public function deposit()
    {
        // TODO: Implement deposit() method.
    }

    public function withdraw()
    {
        // TODO: Implement withdraw() method.
    }

    public function transfer()
    {
        // TODO: Implement transfer() method.
    }

    public function getId()
    {
        // TODO: Implement getId() method.
    }

    public function getAssetInfo()
    {
        // TODO: Implement getAssetInfo() method.
    }

    public function getBalance()
    {
        // TODO: Implement getBalance() method.
    }

    public function getTransactions()
    {
        // TODO: Implement getTransactions() method.
    }
}
