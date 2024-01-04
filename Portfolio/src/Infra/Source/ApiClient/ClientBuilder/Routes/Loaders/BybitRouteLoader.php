<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder\Routes\Loaders;

use App\Domain\Source\Api\Client\LoaderRegistry;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class BybitRouteLoader extends Loader
{
    private YamlFileLoader $yamlFileLoader;

    public function __construct(string $env = null, YamlFileLoader $yamlFileLoader)
    {
        parent::__construct($env);
        $this->yamlFileLoader = $yamlFileLoader;
    }

    public function load(mixed $resource, string $type = null)
    {
        $path = $this->locator->locate($resource);
    }

    public function supports(mixed $resource, string $type = null)
    {
        return $resource === 'bybit' && $type === 'yaml';
    }
}