<?php

namespace App\Domain\Asset\Categories\Crypto;


use App\Domain\Asset\Properties\Fiat\ExchangeableInterface;

interface TokenizedInterface extends ExchangeableInterface
{
    public function getBlockchains();
}
