<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use App\Domain\Source\Api\SourceApiType;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use App\Domain\Storage\Storage;
use App\Infra\Quote\ApiClient\RestClient;
use App\Infra\Source\ApiClient\ClientBuilder\BitgetRestClientBuilder;
use App\Infra\Source\ApiClient\ClientBuilder\BybitRestClientBuilder;
use App\Infra\Source\BitgetApiSource;
use App\Infra\Source\BybitApiSource;
use App\Infra\Storage\CryptoExchange;
use Behat\Behat\Context\Context;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouterInterface;

use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertLessThan;
use function PHPUnit\Framework\assertNotNull;

final class InitContext implements Context
{
    private ?float $startTime = null;
    private RouterInterface $router;
    private BitgetRestClientBuilder $bitGetRestClientBuilder;
    private StorageRepositoryInterface $storageRepository;
    private BybitRestClientBuilder $bybitRestClientBuilder;

    public function __construct(
        StorageRepositoryInterface $storageRepository,
        RouterInterface            $router,
        BitgetRestClientBuilder    $bitGetRestClientBuilder,
        BybitRestClientBuilder $bybitRestClientBuilder
    ) {
        $this->storageRepository = $storageRepository;
        $this->router = $router;
        $this->bitGetRestClientBuilder = $bitGetRestClientBuilder;
        $this->bybitRestClientBuilder = $bybitRestClientBuilder;
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
        $repo = new CryptoExchange($arg1);
        $this->storageRepository->save($repo);
        $repository = $this->storageRepository->findOneBy(['name' => $arg1]);
        assertInstanceOf(Storage::class, $repository);
    }

    /**
     * @Given I request the rest endpoint :arg1 of :arg2 to check if my connexion is alive and my computer is sync
     */
    public function iRequestTheRestEndpointOfToCheckIfMyConnexionIsAliveAndMyComputerIsSync($arg1, $arg2)
    {
        try {
            $route = $this->router->generate(sprintf('%s_server_time', $arg2));
        } catch (RouteNotFoundException $exception) {
            throw new \Exception(sprintf('Route not found. %s', $exception->getMessage()));
        }

        $this->startTime = floor(microtime(true) * 1000);
        switch($arg2){
            case 'bitget':

                $restClient = $this->bitGetRestClientBuilder->getClientForApi(
                    BitgetApiSource::ofType(SourceApiType::Rest->value)
                );
                assertInstanceOf(RestClient::class, $restClient);
                $time = $restClient->getServerTime();
                break;
            case 'bybit':
                $restClient = $this->bybitRestClientBuilder->getClientForApi(
                    BybitApiSource::ofType(SourceApiType::Rest->value)
                );
                assertInstanceOf(RestClient::class, $restClient);
                $time = $restClient->getServerTime();
                break;
            default:
                throw new \LogicException(sprintf("missing provider %s", $arg2)." in ".__CLASS__);
        }

        assertNotNull($time);
        assertLessThan(500,$time - $this->startTime); // bybit often have weird timings
    }
}
