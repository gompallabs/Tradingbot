<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Api\Client\ApiClient;
use App\Domain\Source\Api\Client\ClientBuilder;
use App\Domain\Source\Source;
use App\Infra\Source\ApiClient\Client\Rest\BitgetRestClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BitgetRestClientBuilder implements ClientBuilder
{
    private HttpClientInterface $bitgetRestClient;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(HttpClientInterface $bitgetRestClient, UrlGeneratorInterface $urlGenerator)
    {
        $this->bitgetRestClient = $bitgetRestClient;
        $this->urlGenerator = $urlGenerator;
    }

    public function getClientForApi(Source $source): ApiClient
    {
        return new BitgetRestClient(
            bitgetClient: $this->bitgetRestClient,
            urlGenerator: $this->urlGenerator
        );
    }
}
