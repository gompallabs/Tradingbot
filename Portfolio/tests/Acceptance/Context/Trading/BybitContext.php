<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context\Trading;

use App\Domain\Source\Api\Client\RestApiClient;
use App\Domain\Source\Api\SourceApiType;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientBuilder;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientCredentials;
use App\Infra\Source\Source\BybitApiSource;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use function PHPUnit\Framework\assertTrue;

class BybitContext implements Context
{
    private RestClientCredentials $restClientCredentials;
    private RestApiClient $bybitRestClient;

    public function __construct(
        RestClientCredentials $restClientCredentials,
        RestClientBuilder $restClientBuilder
    ){
        $this->restClientCredentials = $restClientCredentials;
        $this->bybitRestClient = $restClientBuilder->getClientFor(new BybitApiSource(SourceApiType::Rest));
    }

    /**
     * @Given I have a trading token for :arg1
     */
    public function iHaveATradingTokenFor($arg1)
    {
        assertTrue($this->bybitRestClient->hasCredentials());
    }

    /**
     * @Then I can request wallet balance for :arg1
     */
    public function iCanRequestWalletBalanceFor($arg1)
    {
        $balance = $this->bybitRestClient->accountBalance();

    }

    /**
     * @Then I request the latest :arg1 derivative price
     */
    public function iRequestTheLatestDerivativePrice($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I place a buy order in USDT of :arg1 USDT at limit price inferior of :arg2 under market price
     */
    public function iPlaceABuyOrderInUsdtOfUsdtAtLimitPriceInferiorOfUnderMarketPrice($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I list the current orders
     */
    public function iListTheCurrentOrders()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see my buy order of :arg1 USDT
     */
    public function iShouldSeeMyBuyOrderOfUsdt($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I cancel the order
     */
    public function iCancelTheOrder()
    {
        throw new PendingException();
    }

    /**
     * @Then I list orders
     */
    public function iListOrders()
    {
        throw new PendingException();
    }

    /**
     * @Then the previous order should not be listed
     */
    public function thePreviousOrderShouldNotBeListed()
    {
        throw new PendingException();
    }

}