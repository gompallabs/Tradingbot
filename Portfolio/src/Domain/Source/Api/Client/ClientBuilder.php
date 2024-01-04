<?php

namespace App\Domain\Source\Api\Client;

use App\Domain\Source\Source;

interface ClientBuilder
{
    public function getClientForApi(Source $source): ApiClient;
}
