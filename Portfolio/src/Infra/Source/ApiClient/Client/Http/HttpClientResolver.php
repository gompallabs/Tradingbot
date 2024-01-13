<?php

namespace App\Infra\Source\ApiClient\Client\Http;

use App\Domain\Source\Api\Client\HttpClientRegistry as HttpClientRegistryInterface;
use App\Domain\Source\Source;
use App\Infra\Source\ApiClient\Client\Http\Rest\RestClientList;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientResolver implements HttpClientRegistryInterface
{
    private array $clients;

    public function __construct(
        HttpClientInterface $binanceRestClient,
        HttpClientInterface $bybitRestClient,
        HttpClientInterface $bitgetRestClient,
        HttpClientInterface $coinbaseRestClient,
        HttpClientInterface $krakenRestClient
    ) {
        $this->clients['binance'] = $binanceRestClient;
        $this->clients['bybit'] = $bybitRestClient;
        $this->clients['bitget'] = $bitgetRestClient;
        $this->clients['coinbase'] = $coinbaseRestClient;
        $this->clients['kraken'] = $krakenRestClient;
    }

    public function getHttpClientFor(Source $source): HttpClientInterface
    {
        if (!in_array($source->getName(), array_map(function ($provider) {
            return $provider->name;
        }, RestClientList::cases()))) {
            throw new \RuntimeException(sprintf('Unsupported provider %s, in %s', $source->getName(), __CLASS__));
        }

        return $this->clients[$source->getName()];
    }
}
