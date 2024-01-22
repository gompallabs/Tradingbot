<?php

namespace App\Infra\Source\ApiClient\Client\Http\Rest;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AssetStorageRestClient
{
    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
}
