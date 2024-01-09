<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\Client\Http\Rest;

use App\Domain\Source\Api\Client\RestApiClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class BitgetRestClient extends AssetStorageRestClient implements RestApiClient
{
    private HttpClientInterface $client;
    private ?string $apiKey = null;
    private ?string $apiKeySecret = null;

    public function __construct(
        HttpClientInterface $client,
        UrlGeneratorInterface $urlGenerator,
    ) {
        parent::__construct($urlGenerator);
        $this->client = $client;
    }


    public function hasCredentials(): bool
    {
        return ($this->apiKey !== null)
            && strlen($this->apiKey) > 0
            && ($this->apiKeySecret !== null)
            && strlen($this->apiKeySecret) > 0;
    }


    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        // TODO: Implement request() method.
    }

    public function stream(iterable|ResponseInterface $responses, float $timeout = null): ResponseStreamInterface
    {
        // TODO: Implement stream() method.
    }

    public function withOptions(array $options): static
    {
        // TODO: Implement withOptions() method.
    }


    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setApiKeySecret(?string $apiKeySecret): void
    {
        $this->apiKeySecret = $apiKeySecret;
    }

    public function getServerTime(): int
    {
        $url = $this->urlGenerator->generate('server_time');
        $response = $this->client->request(
            method: 'GET',
            url: $url,
            options: []
        );

        $response = json_decode($response->getContent(), true, JSON_PRETTY_PRINT);

        return (int) $response['data']['serverTime'];
    }

    public function accountBalance()
    {
        // TODO: Implement accountBalance() method.
    }
}
