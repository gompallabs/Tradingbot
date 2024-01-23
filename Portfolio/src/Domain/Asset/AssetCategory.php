<?php

declare(strict_types=1);

namespace App\Domain\Asset;

/*
 * Here Category design macro concept of Security or Commodity
 */
enum AssetCategory: string
{
    case Crypto = 'Crypto';         // meaning shitcoins = a token that mimic other asset categories, with a big centralized ownership
                                    // including security-like and bond-like ETH, SOL, USDT. Including meme-coins that mimic ... nothing = being "only" FIAT
    case Commodity = 'Commodity';   // bitcoin is classified as a commodity and might become a currency
    case Currency = 'Currency';
    case Equity = 'Equity';
    case Derivative = 'Derivative';
}
