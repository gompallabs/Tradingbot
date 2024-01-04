<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Transaction;

interface Transaction
{
    public function input();

    public function execute();

    public function details();

    public function confirmation();
}
