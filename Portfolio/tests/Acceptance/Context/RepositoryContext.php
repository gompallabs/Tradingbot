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
use App\Infra\Source\Source\BitgetApiSource;
use App\Infra\Source\Source\BybitApiSource;
use App\Infra\Source\Source\JupiterApiSource;
use Behat\Behat\Context\Context;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertNotEmpty;
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

    private array $spot = [];
    private array $fut = [];

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
            'bitget' => BitgetApiSource::ofType('rest'),
            'bybit' => BybitApiSource::ofType('rest'),
            'jupiter' => JupiterApiSource::ofType('rest'),
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
                $this->response = $this->client->accountBalance([
                    'coin' => 'USDT',
                ]);
                assertNotEmpty($this->response);
                break;
            case 'bitget':
                assertInstanceOf(BitgetRestClient::class, $this->client);
                $balance = $this->client->accountBalance(['productType' => 'USDT-FUTURES']);
                assertArrayHasKey('marginCoin', $balance);
                assertEquals('USDT', $balance['marginCoin']);
                break;
            default:
                throw new \LogicException('missing client in '.__CLASS__);
        }
    }

    /**
     * @Then the response should be an array with several balances
     */
    public function theResponseShouldBeAnArrayWithSeveralBalances()
    {
        $provider = $this->provider;
        $response = $this->response;
        if ($provider === 'bybit') {
            $balance = array_shift($response);
            assertArrayHasKey('totalAvailableBalance', $balance);
            assertArrayHasKey('totalWalletBalance', $balance);
            assertArrayHasKey('coin', $balance);
        }
    }

    /**
     * @Then I request spot account info
     */
    public function iRequestSpotAccountInfo()
    {
        $accountInfo = $this->client->getSpotAccountInfo();
        if ($this->client instanceof BitgetRestClient) {
            assertArrayHasKey('userId', $accountInfo);
        }
        if ($this->client instanceof BybitRestClient) {
            assertArrayHasKey('marginMode', $accountInfo);
        }
    }

    /**
     * @Then I query the spot balance
     */
    public function iQueryTheSpotBalance()
    {
        if ($this->client instanceof BitgetRestClient) {
            $this->spot = $this->client->getSpotAccountAssets();
        }
        if ($this->client instanceof BybitRestClient) {
            $this->spot = $this->client->getSpotAccountAssets();
        }
    }

    /**
     * @Then I query the futures positions
     */
    public function iQueryTheFuturesPositions()
    {
        if ($this->client instanceof BitgetRestClient) {
            $this->fut = $this->client->getFuturesPositions(['USDT-FUTURES']);
        }
        if ($this->client instanceof BybitRestClient) {
            $this->fut = $this->client->getFuturesPositions(['linear']);
        }
    }

    /**
     * @Then I save the results, only quantities
     */
    public function iSaveTheResultsOnlyQuantities()
    {
        dump($this->spot);
        dd($this->fut);
    }
}
