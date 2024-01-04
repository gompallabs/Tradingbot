<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Api\Client\ApiClient;
use App\Domain\Source\Api\Client\ClientBuilder;
use App\Domain\Source\Source;
use App\Infra\Source\ApiClient\Client\Rest\BybitRestClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BybitRestClientBuilder implements ClientBuilder
{
    private HttpClientInterface $bybitRestClient;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(HttpClientInterface $bybitRestClient, UrlGeneratorInterface $urlGenerator)
    {
        $this->bybitRestClient = $bybitRestClient;
        $this->urlGenerator = $urlGenerator;
    }

    public function getClientForApi(Source $source): ApiClient
    {
        return new BybitRestClient(
            bybitClient: $this->bybitRestClient,
            urlGenerator: $this->urlGenerator
        );
    }
}