<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\Client\Rest;

use App\Infra\Quote\ApiClient\RestClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class BitgetRestClient implements RestClient
{
    private HttpClientInterface $client;
    private UrlGeneratorInterface $urlGenerator;
    private ?string $apiKey;
    private ?string $apiKeySecret;
    

    public function __construct(
        HttpClientInterface $bitgetClient,
        UrlGeneratorInterface $urlGenerator,
        ?string $apiKey = null,
        ?string $apiKeySecret = null
    ) {
        $this->client = $bitgetClient;
        $this->urlGenerator = $urlGenerator;
        $this->apiKey = $apiKey;
        $this->apiKeySecret = $apiKeySecret;
    }

    public function getServerTime(): int
    {
        $url = $this->urlGenerator->generate('bitget_server_time');
        $response = $this->client->request(
            method: 'GET',
            url: $url,
            options: []
        );

        $response = json_decode($response->getContent(), true, JSON_PRETTY_PRINT);

        return (int) $response['data']['serverTime'];
    }

    public function hasCredentials(): bool
    {
        return ($this->apiKey !== null)
            && strlen($this->apiKey) > 0
            && ($this->apiKeySecret !== null)
            && strlen($this->apiKeySecret) > 0;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setApiKeySecret(?string $apiKeySecret): void
    {
        $this->apiKeySecret = $apiKeySecret;
    }
}
