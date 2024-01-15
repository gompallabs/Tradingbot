<?php

namespace App\Domain\Exchange;

use Doctrine\Common\Collections\Collection;

interface ExchangeInterface
{
    public function isCentralized(): bool;

    public function getListedAssets(): Collection;

    public function continuouTrading(): bool;
}
