<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Source\Api\Client\RestApiClient;
use App\Domain\Source\Api\SourceApiType;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Domain\Storage\Storage;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientBuilder;
use App\Infra\Source\Source\BitgetApiSource;
use App\Infra\Source\Source\BybitApiSource;
use App\Infra\Storage\CryptoExchange;
use Behat\Behat\Context\Context;
use Symfony\Component\Routing\RouterInterface;

use Symfony\Component\Uid\Uuid;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertLessThan;
use function PHPUnit\Framework\assertNotNull;

final class InitContext implements Context
{
    private ?float $startTime = null;
    private StorageRepositoryInterface $storageRepository;
    private RestClientBuilder $restClientBuilder;

    public function __construct(
        StorageRepositoryInterface $storageRepository,
        RouterInterface $router,
        RestClientBuilder $restClientBuilder
    ) {
        $this->storageRepository = $storageRepository;
        $this->router = $router;
        $this->restClientBuilder = $restClientBuilder;
    }

    /**
     * @Given I measure time in milliseconds
     */
    public function iMeasureTimeInMilliseconds()
    {
        $this->startTime = floor(microtime(true) * 1000);
        echo sprintf('start-time: %s', $this->startTime);
        assert(strlen((string) $this->startTime) === 14);
    }

    /**
     * @Given I have a crypto exchange named :arg1
     */
    public function iHaveACryptoExchangeNamed($arg1)
    {
        $storage = new CryptoExchange(id: Uuid::v4(), name: $arg1);
        $this->storageRepository->save($storage);
        $dbStorage = $this->storageRepository->findOneBy(['name' => $arg1]);
        assertInstanceOf(Storage::class, $dbStorage);
    }

    /**
     * @Given I request the rest endpoint :arg1 of :arg2 to check if my connexion is alive and my computer is sync
     */
    public function iRequestTheRestEndpointOfToCheckIfMyConnexionIsAliveAndMyComputerIsSync($arg1, $arg2)
    {
        $this->startTime = floor(microtime(true) * 1000);

        switch ($arg2) {
            case 'bitget':
                $source = BitgetApiSource::ofType(SourceApiType::Rest->value);
                break;
            case 'bybit':
                $source = BybitApiSource::ofType(SourceApiType::Rest->value);
                break;
            default:
                throw new \LogicException(sprintf('missing provider %s', $arg2).' in '.__CLASS__);
        }

        $restClient = $this->restClientBuilder->getClientFor(source: $source, public: true);
        assertInstanceOf(RestApiClient::class, $restClient);
        $time = $restClient->getServerTime();

        assertNotNull($time);
        assertLessThan(1000, $time - $this->startTime); // bybit often have weird timings
    }
}
