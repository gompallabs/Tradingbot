<?php

namespace App\Domain\Source\Api\Client;

use App\Domain\Source\Source;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface HttpClientRegistry
{
    public function getHttpClientFor(Source $source): HttpClientInterface;
}