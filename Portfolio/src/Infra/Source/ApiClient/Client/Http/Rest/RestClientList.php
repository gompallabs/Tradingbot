<?php

namespace App\Infra\Source\ApiClient\Client\Http\Rest;

enum RestClientList: string
{
    case bybit = BybitRestClient::class;
    case bitget = BitgetRestClient::class;
}
