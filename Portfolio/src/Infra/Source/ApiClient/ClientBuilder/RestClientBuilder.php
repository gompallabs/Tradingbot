<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Api\Client\ApiClient;
use App\Domain\Source\Api\Client\ClientBuilder;
use App\Domain\Source\Source;
use App\Infra\Source\ApiClient\Client\Rest\ClientList;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestClientBuilder implements ClientBuilder
{
    private RouterInterface $router;

    private Source $source;
    private HttpClientRegistry $httpClientRegistry;
    private ApiCredentials $apiCredentials;

    public function __construct(
        ApiCredentials $apiCredentials,
        HttpClientRegistry $httpClientRegistry,
        RouterInterface $router,

    ) {
        $this->apiCredentials = $apiCredentials;
        $this->httpClientRegistry = $httpClientRegistry;
        $this->router = $router;
    }

    public function getClientForApi(Source $source): ApiClient
    {
        $clientName = ClientList::tryFrom($source->getName());
        $client = new $clientName(
            $this->getHttpClient(),
            $this->getRouteCollection()
        );
    }

//HttpClientInterface $bitgetClient,
//UrlGeneratorInterface $urlGenerator,
//?string $apiKey = null,
//?string $apiKeySecret = null

    private function getRouteCollection(): RouteCollection
    {
        $collection = $this->router->getRouteCollection();
        $subCollection = new RouteCollection();
        foreach ($collection as $routes){
            if($this->source->getName() === $routes['name']){
                $subCollection->add($routes['name']);
            }
        }
        return $subCollection;
    }

    private function getHttpClient(): HttpClientInterface
    {
        return $this->httpClientRegistry->getClientForSource($this->source);
    }

    private function getApiCredentials(): array
    {
        return $this->apiCredentials->getCredentialsForSource($this->source);
    }
}