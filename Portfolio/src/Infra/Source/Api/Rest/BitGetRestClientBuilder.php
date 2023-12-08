<?php

declare(strict_types=1);

namespace App\Infra\Source\Api\Rest;

use App\Domain\Source\Api\ApiClient;
use App\Domain\Source\Api\ApiType;
use App\Domain\Source\Api\ClientBuilder;
use App\Domain\Source\Source;

class BitGetRestClientBuilder implements ClientBuilder
{
    public function getClientForApi(ApiType $apiType, Source $source): ApiClient
    {

    }
}