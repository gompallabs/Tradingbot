<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Api\Client\ApiClient;
use App\Domain\Source\Api\Client\ClientBuilder;
use App\Domain\Source\Api\Client\HttpClientRegistry;
use App\Domain\Source\Api\Client\RestApiClient;
use App\Domain\Source\Source;
use App\Infra\Source\ApiClient\Client\Http\Rest\RestClientList;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

class RestClientBuilder implements ClientBuilder
{
    private HttpClientRegistry $httpClientRegistry;
    private RestClientCredentials $clientsCredentials;
    private RestRouteLoader $routeLoader;


    public function __construct(
        HttpClientRegistry    $httpClientRegistry,
        RestClientCredentials $clientsCredentials,
        RestRouteLoader       $routeLoader,
    ) {
        $this->httpClientRegistry = $httpClientRegistry;
        $this->clientsCredentials = $clientsCredentials;
        $this->routeLoader = $routeLoader;
    }

    public function getClientFor(Source $source, bool $public = false): RestApiClient
    {
        $routes = $this->routeLoader->getRoutesForSource($source);
        $clientClass = match($source->getName()){
            'binance' => RestClientList::binance->value,
            'bitget' => RestClientList::bitget->value,
            'bybit' => RestClientList::bybit->value,
            'coinbase' => RestClientList::coinbase->value,
            'kraken' => RestClientList::kraken->value,
        };

        $publicClient = new $clientClass(
            $this->httpClientRegistry->getHttpClientFor($source),
            new UrlGenerator(
                routes: $routes,
                context: new RequestContext()
            ),
        );

        if($public === false){
            return $this->setClientCredentials($publicClient, $source);
        }

        return $publicClient;
    }

    public function setClientCredentials(RestApiClient $client, Source $source): RestApiClient
    {
        $credentials = $this->clientsCredentials->getCredentials($source);
        $client->setApiKey($credentials[0]['api_key']);
        $client->setApiKeySecret($credentials[1]['api_key_secret']);
        return $client;
    }
}