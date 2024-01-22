<?php

namespace App\Tests\Unit;

use App\Domain\Source\Api\SourceApiType;
use App\Infra\Source\ApiClient\Client\Http\Rest\BitgetRestClient;
use App\Infra\Source\ApiClient\ClientBuilder\RestClientCredentials;
use App\Infra\Source\Source\BitgetApiSource;
use App\Tests\Mock\BitgetContractV2Mock;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BitgetAccountBalanceTest extends KernelTestCase
{
    private BitgetRestClient $client;
    private RestClientCredentials $clientCredentials;

    protected function setUp(): void
    {
        $clientBuilder = self::getContainer()->get('App\Infra\Source\ApiClient\ClientBuilder\RestClientBuilder');
        $bitgetRestClient = $clientBuilder->getClientFor(new BitgetApiSource(SourceApiType::Rest));
        self::assertInstanceOf(BitgetRestClient::class, $bitgetRestClient);
        $this->client = $bitgetRestClient;
        $this->clientCredentials = self::getContainer()->get('App\Infra\Source\ApiClient\ClientBuilder\RestClientCredentials');
    }

    public function testAccountBalance()
    {
        // use sdk in test env
        $credentials = $this->clientCredentials->getCredentials(new BitgetApiSource(SourceApiType::Rest));
        $bitgetAccount = new BitgetContractV2Mock(
            [
                'key' => $credentials['api_key'],
                'secret' => $credentials['api_key_secret'],
                'host' => 'https://api.bitget.com/',
                'passphrase' => $credentials['api_key_passphrase'],
                'options' => [],
                'platform' => 'contract_v2',
                'version' => 'v2',
            ]
        );

        $legacyCallBalance = $bitgetAccount->getAccounts(['productType' => 'USDT-FUTURES'])['data'][0];
        self::assertArrayHasKey('accountEquity', $legacyCallBalance);
        $legacyBalance = $legacyCallBalance['accountEquity'];

        // then compare to our code's result
        $balanceCall = $this->client->accountBalance(['productType' => 'USDT-FUTURES']);
        self::assertArrayHasKey('accountEquity', $legacyCallBalance);
        $balance = $balanceCall['accountEquity'];
        self::assertEquals(round($legacyBalance, 5), round($balance, 5));
    }
}
