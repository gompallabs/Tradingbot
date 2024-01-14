<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\Client\Http\Rest;

use App\Domain\Source\Api\Client\RestApiClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

class BybitRestClient extends AssetStorageRestClient implements RestApiClient
{
    public const BYBIT_ACCOUNT_TYPE = [
        'UNIFIED' => 'UNIFIED',
        'CONTRACT' => 'CONTRACT', // trade inverse
    ];

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


    /*
     * Used for signed requests
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $headers = $this->getEncryptedHeaders($url);

        return $this->client->request(
            method: 'GET',
            url: $url,
            options: $headers
        );
    }

    private function getEncryptedHeaders(string $url): array
    {
        $requestParams = parse_url($url);
        $queryParams = $requestParams['query'];
        $timestamp = time() * 1000;
        $signature = $this->signature($queryParams, $timestamp);

        return [
            'headers' => [
                'X-BAPI-SIGN' => $signature,
                'X-BAPI-API-KEY' => $this->apiKey,
                'X-BAPI-TIMESTAMP' => $timestamp,
                'X-BAPI-RECV-WINDOW' => 5000,
            ],
        ];
    }

    private function signature($params, $timestamp): string
    {
        $receiveWindow = '5000';
        $signatureParameters = $timestamp.$this->apiKey.$receiveWindow.$params;

        return hash_hmac('sha256', $signatureParameters, $this->apiKeySecret);
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

        return (int) ((int) $response['result']['timeNano'] / 1000000);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function accountBalance(array $options = null)
    {
        $urlParams = ['accountType' => self::BYBIT_ACCOUNT_TYPE['UNIFIED']];

        if ($options !== null) {
            $urlParams = array_merge(
                $urlParams,
                $options
            );
        }

        $url = $this->urlGenerator->generate('wallet_balance',
            $urlParams
        );
        $response = $this->request(
            method: 'GET',
            url: $url,
            options: []
        );

        $data = json_decode($response->getContent(), true, JSON_PRETTY_PRINT);

        return $data['result']['list'];
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function setApiKeySecret(?string $apiKeySecret): void
    {
        $this->apiKeySecret = $apiKeySecret;
    }

    public function setApiKeyPassphrase(?string $apiKeyPassphrase): void
    {
        $this->apiKeyPassphrase = $apiKeyPassphrase;
    }

    public function stream(iterable|ResponseInterface $responses, float $timeout = null): ResponseStreamInterface
    {
        // TODO: Implement stream() method.
    }

    public function withOptions(array $options): static
    {
        // TODO: Implement withOptions() method.
    }
}
