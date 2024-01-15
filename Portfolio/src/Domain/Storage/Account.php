<?php

declare(strict_types=1);

namespace App\Domain\Storage;

use App\Domain\Storage\Orm\Doctrine\AccountType;
use Symfony\Component\Uid\Uuid;

interface Account
{
    public function getId(): Uuid;

    public function getType(): AccountType;
    public function getStorage(): Storage;

    public function deposit();
    public function withdraw();
    public function transfer();
    public function getBalance();
    public function getTransactions();
}
