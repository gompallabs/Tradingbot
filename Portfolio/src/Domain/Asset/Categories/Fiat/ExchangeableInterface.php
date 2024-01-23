<?php

namespace App\Domain\Asset\Categories\Fiat;


/*
 * Fiat is a medium of exchange, accepted and recognized, portable, fungible
 * Supposed uniform, durable and being a legal tender
 * */
interface ExchangeableInterface
{
    public function getExchangeMethods(): array;
}