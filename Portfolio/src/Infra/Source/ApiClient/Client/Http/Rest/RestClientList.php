<?php

namespace App\Infra\Source\ApiClient\Client\Http\Rest;

enum RestClientList: string
{
    case binance = BinanceRestClient::class;
    case bybit = BybitRestClient::class;
    case bitget = BitgetRestClient::class;
    case coinbase = CoinbaseRestClient::class;
    case kraken = KrakenRestClient::class;
}
