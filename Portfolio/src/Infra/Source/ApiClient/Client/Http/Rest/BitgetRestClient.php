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
    private ?string $apiKeyPassphrase = null;

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
        $headers = $this->getEncryptedHeaders($url, $method);

        return $this->client->request(
            method: 'GET',
            url: $url,
            options: $headers
        );
    }

    private function getEncryptedHeaders(string $url, string $method, string $body = null): array
    {
        $timestamp = time() * 1000;
        $signature = $this->signature(
            timestamp: $timestamp,
            method: $method,
            url: $url,
        );

        $headers = [
            'headers' => [
                'ACCESS-KEY' => $this->apiKey,
                'ACCESS-SIGN' => $signature,
                'ACCESS-TIMESTAMP' => $timestamp,
                'ACCESS-PASSPHRASE' => $this->apiKeyPassphrase,
                'locale' => 'en-US',
                'timeout' => 60,
            ],
        ];

        return $headers;
    }

    private function signature(int $timestamp, string $method, string $url, string $body = null): string
    {
        return base64_encode(hash_hmac(
            'sha256',
            trim((string) $timestamp.strtoupper($method).$url.(string) $body),
            $this->apiKeySecret,
            true
        )
        );
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

    public function accountBalance(array $options)
    {
        $url = $this->urlGenerator->generate('wallet_balance', $options);
        $response = $this->request(
            method: 'GET',
            url: $url
        );

        $data = json_decode($response->getContent(), true, JSON_PRETTY_PRINT);

        return $data['data'][0];
    }

    public function setApiKeyPassphrase(?string $apiKeyPassphrase): void
    {
        $this->apiKeyPassphrase = $apiKeyPassphrase;
    }
}
