<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder;

use App\Domain\Source\Api\Client\RouteLoader;
use App\Domain\Source\Source;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RestRouteLoader implements RouteLoader
{
    private string $projectPath;
    private array $supported;

    public function __construct(string $projectPath, array $supported)
    {
        $this->projectPath = $projectPath;
        $this->supported = $supported;
    }

    public function getRoutesForSource(Source $source): RouteCollection
    {
        $fileLocator = new FileLocator();
        $loaderResolver = new LoaderResolver([new YamlFileLoader($fileLocator)]);
        $delegatingLoader = new DelegatingLoader($loaderResolver);
        return $delegatingLoader->load(
            sprintf($this->projectPath.'/config/routes/external/%s.yaml', $source->getName())
        );
    }

    public function supports(Source $source, string $type = null): bool
    {
        return in_array(
            trim(strtolower($source->getName())),
            $this->supported
        );
    }
}