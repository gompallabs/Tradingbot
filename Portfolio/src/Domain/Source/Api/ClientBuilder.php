<?php

namespace App\Domain\Source\Api;

use App\Domain\Source\Source;

interface ClientBuilder
{
    public function getClientForApi(ApiType $apiType, Source $source): ApiClient;
}