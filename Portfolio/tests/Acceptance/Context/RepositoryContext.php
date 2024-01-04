<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Source\Api\Orm\Doctrine\ApiErrorCodesRepositoryInterface;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Infra\Source\ApiClient\Client\Rest\BitgetRestClient;
use App\Infra\Source\ApiClient\ClientBuilder\BitgetRestClientBuilder;
use App\Infra\Source\BitgetApiSource;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use function PHPUnit\Framework\assertGreaterThan;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

class RepositoryContext implements Context
{
    private StorageRepositoryInterface $assetRepositoryRepository;
    private ApiErrorCodesRepositoryInterface $errorCodesRepository;
    private string $bybitRestApiError;
    private string $bitgetRestApiError;
    private string $html;
    private array $errorCodes;
    private string $bybitApiKey;
    private string $bybitApiKeySecret;

    private BitgetRestClientBuilder $bitGetRestClientBuilder;

    private BitgetRestClient $bitGetRestClient;

    public function __construct(
        BitgetRestClientBuilder          $bitGetRestClientBuilder,
        StorageRepositoryInterface       $assetRepositoryRepository,
        ApiErrorCodesRepositoryInterface $errorCodesRepository,
        string                           $bybitRestApiError,
        string                           $bitgetRestApiError,
        string                           $bybitApiKey,
        string                           $bybitApiKeySecret
    ) {
        $this->bitGetRestClientBuilder = $bitGetRestClientBuilder;
        $this->assetRepositoryRepository = $assetRepositoryRepository;
        $this->errorCodesRepository = $errorCodesRepository;
        $this->bybitRestApiError = $bybitRestApiError;
        $this->bitgetRestApiError = $bitgetRestApiError;
        $this->bybitApiKey = $bybitApiKey;
        $this->bybitApiKeySecret = $bybitApiKeySecret;
    }

    /**
     * @Given I have a read-only api key for :arg1
     */
    public function iHaveAReadOnlyApiKeyFor($arg1)
    {
        $apiKey = $this->bybitApiKey;
        assertGreaterThan(0, strlen($apiKey));
    }

    /**
     * @Given I have a secret for api key of :arg1
     */
    public function iHaveASecretForApiKeyOf($arg1)
    {
        $apiKeySecret = $this->bybitApiKeySecret;
        assertGreaterThan(0, strlen($apiKeySecret));
    }

    /**
     * @Given I instanciate a rest api client for :arg1
     */
    public function iInstanciateARestApiClientFor($arg1)
    {
        $bitgetSource = BitgetApiSource::type('rest');
        $client = $this->bitGetRestClientBuilder->getClientForApi(source: $bitgetSource);
        assertInstanceOf(BitgetRestClient::class, $client);
        $this->bitGetRestClient = $client;
    }

    /**
     * @Then I shoud have a client with credentials
     */
    public function iShoudHaveAClientWithCredentials()
    {
        assertTrue($this->bitGetRestClient->hasCredentials());
    }

    /**
     * @Then should be able to sign my :arg1 requests to the Api following documentation
     */
    public function shouldBeAbleToSignMyRequestsToTheApiFollowingDocumentation($arg1)
    {
        throw new PendingException();
    }
}
