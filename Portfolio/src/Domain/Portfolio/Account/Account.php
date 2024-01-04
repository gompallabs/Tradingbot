<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Account;

interface Account
{
    public function deposit();

    public function withdraw();

    public function transfer();

    public function getId();

    public function getAssetInfo();

    public function getBalance();

    public function getTransactions();
}
