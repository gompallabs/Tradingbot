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
        $array = array_key_exists($source->getName(), $this->apiKeys) ?
            $this->apiKeys[$source->getName()] : null;

        return count($array) > 0 ? self::getKeysFromArray($array) : null;
    }

    public static function getKeysFromArray(array $array): array
    {
        $creds = [];
        $creds['api_key'] = $array[0]['api_key'];
        $creds['api_key_secret'] = $array[1]['api_key_secret'];

        if (count($array) > 2) {
            $creds['api_key_passphrase'] = $array[2]['api_key_passphrase'];
        }

        return $creds;
    }
}
