<?php

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Source;

class RestClientCredentials
{
    private array $apiKeys;

    public function __construct(array $apiKeys)
    {
        $this->apiKeys = $apiKeys;
    }

    public function getCredentials(Source $source): ?array
    {
        return array_key_exists($source->getName(), $this->apiKeys) ?
            $this->apiKeys[$source->getName()] : null;
    }
}