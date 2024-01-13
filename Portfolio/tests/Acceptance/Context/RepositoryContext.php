<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Source\Api\Client\RestApiClient;
use App\Domain\Source\Api\Orm\Doctrine\ApiErrorCodesRepositoryInterface;
use App\Domain\Source\Source;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Infra\Source\ApiClient\Client\Http\Rest\BitgetRestClient;
use App\Infra\Source\ApiClient\Client\Http\Rest\BybitRestClient;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientBuilder;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientCredentials;
use App\Infra\Source\Source\BinanceApiSource;
use App\Infra\Source\Source\BitgetApiSource;
use App\Infra\Source\Source\BybitApiSource;
use App\Infra\Source\Source\CoinbaseApiSource;
use App\Infra\Source\Source\JupiterApiSource;
use App\Infra\Source\Source\KrakenApiSource;
use Behat\Behat\Context\Context;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

class RepositoryContext implements Context
{
    private StorageRepositoryInterface $assetRepositoryRepository;
    private ApiErrorCodesRepositoryInterface $errorCodesRepository;
    private RestApiClient $client;
    private RestClientCredentials $clientsCredentials;
    private RestClientBuilder $restClientBuilder;

    private array $response = [];

    private ?string $provider = null;

    public function __construct(
        StorageRepositoryInterface $assetRepositoryRepository,
        ApiErrorCodesRepositoryInterface $errorCodesRepository,
        RestClientCredentials $clientsCredentials,
        RestClientBuilder $restClientBuilder
    ) {
        $this->assetRepositoryRepository = $assetRepositoryRepository;
        $this->errorCodesRepository = $errorCodesRepository;
        $this->clientsCredentials = $clientsCredentials;
        $this->restClientBuilder = $restClientBuilder;
    }

    private function getSource(string $name): Source
    {
        return match ($name) {
            'binance' => BinanceApiSource::ofType('rest'),
            'bitget' => BitgetApiSource::ofType('rest'),
            'bybit' => BybitApiSource::ofType('rest'),
            'coinbase' => CoinbaseApiSource::ofType('rest'),
            'jupiter' => JupiterApiSource::ofType('rest'),
            'kraken' => KrakenApiSource::ofType('rest'),
            default => throw new \RuntimeException(sprintf('unsupported provider %s in %s', $name, __CLASS__))
        };
    }

    private function getApiKeys(string $name): array
    {
        return $this->clientsCredentials->getCredentials($this->getSource($name));
    }

    /**
     * @Given I have a read-only api key for :arg1
     */
    public function iHaveAReadOnlyApiKeyFor($arg1)
    {
        $apiKey = $this->clientsCredentials->getCredentials(
            $this->getSource($arg1)
        );
        assertGreaterThan(0, strlen($apiKey['api_key']));
    }

    /**
     * @Given I have a secret for api key of :arg1
     */
    public function iHaveASecretForApiKeyOf($arg1)
    {
        $keys = $this->getApiKeys($arg1);
        $apiKeySecret = $keys['api_key_secret'];
        assertGreaterThan(0, strlen($apiKeySecret));
    }

    /**
     * @Given I instanciate a rest api client for :arg1
     */
    public function iInstanciateARestApiClientFor($arg1)
    {
        $source = $this->getSource($arg1);
        $client = $this->restClientBuilder->getClientFor(source: $source);
        assertInstanceOf(RestApiClient::class, $client);
        $this->client = $client;
        $this->provider = $arg1;
    }

    /**
     * @Then I shoud have a client with credentials
     */
    public function iShoudHaveAClientWithCredentials()
    {
        assertTrue($this->client->hasCredentials());
    }

    /**
     * @Given I request account balance for :arg1
     */
    public function iRequestAccountBalanceFor($arg1)
    {
        switch ($this->provider) {
            case 'bybit':
                assertInstanceOf(BybitRestClient::class, $this->client);
                break;
            case 'bitget':
                assertInstanceOf(BitgetRestClient::class, $this->client);
                break;
            default:
                throw new \LogicException('missing client in '.__CLASS__);
        }
        $balance = $this->client->accountBalance(['productType' => 'USDT-FUTURES']);
        assertArrayHasKey('marginCoin', $balance);
        assertEquals('USDT', $balance['marginCoin']);
    }

    /**
     * @Then the response should be an array with several balances
     */
    public function theResponseShouldBeAnArrayWithSeveralBalances()
    {
        $provider = $this->provider;
        $response = $this->response;
        if ($provider === 'bybit') {
            $payload = $response['result']['list'];
            $meta = array_keys($payload[0]);
            $balance = $payload[0]['coin'];
            assertGreaterThan(1, count($balance));
        }
    }
}
