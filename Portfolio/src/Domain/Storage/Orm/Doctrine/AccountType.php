<?php

declare(strict_types=1);

namespace App\Domain\Storage\Orm\Doctrine;

enum AccountType: string
{
    case FIAT = 'FIAT';                                     // ex: bank account
    case FIAT_ACCRUED = 'FIAT_ACCRUED';                     // ex: savings account with accrual
    case ASSET_SPOT = 'ASSET_SPOT';                         // ex: your basic crypto asset account
    case ASSET_FUTURES_TRADING = 'ASSET_FUTURES_TRADING';           // ex: a margin trading account for futures
    case ASSET_SPOT_MARGIN_TRADING = 'ASSET_SPOT_MARGIN_TRADING';   // ex: a margin trading account for spot
}