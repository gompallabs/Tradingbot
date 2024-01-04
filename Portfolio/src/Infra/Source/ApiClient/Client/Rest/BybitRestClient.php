<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\Client\Rest;

use App\Infra\Quote\ApiClient\RestClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BybitRestClient implements RestClient
{
    private HttpClientInterface $bybitRestClient;
    private UrlGeneratorInterface $urlGenerator;

    private ?string $apiKey;
    private ?string $apiKeySecret;

    public function __construct(
        HttpClientInterface $bybitClient,
        UrlGeneratorInterface $urlGenerator,
        ?string $apiKey = null,
        ?string $apiKeySecret = null
    ) {
        $this->bybitRestClient = $bybitClient;
        $this->urlGenerator = $urlGenerator;
        $this->apiKey = $apiKey;
        $this->apiKeySecret = $apiKeySecret;
    }

    public function hasCredentials(): bool
    {
        // TODO: Implement hasCredentials() method.
    }

    public function setApiKey(?string $apiKey): void
    {
        // TODO: Implement setApiKey() method.
    }

    public function setApiKeySecret(?string $apiKey): void
    {
        // TODO: Implement setApiKeySecret() method.
    }

    public function getServerTime(): int
    {
        $url = $this->urlGenerator->generate('bybit_server_time');
        $response = $this->bybitRestClient->request(
            method: 'GET',
            url: $url,
            options: []
        );

        $response = json_decode($response->getContent(), true, JSON_PRETTY_PRINT);
        return (int)((int) $response['result']['timeNano'] / 1000000);
    }
}