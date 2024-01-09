<?php

namespace App\Domain\Source\Api\Client;

use App\Domain\Source\Source;

interface ClientBuilder
{
    public function getClientFor(Source $source, bool $public = false): RestApiClient;
}
