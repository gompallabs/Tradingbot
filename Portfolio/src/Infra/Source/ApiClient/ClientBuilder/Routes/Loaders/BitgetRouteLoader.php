<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder\Routes\Loaders;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class BitgetRouteLoader extends Loader
{
    public function load(mixed $resource, string $type = null)
    {
        // TODO: Implement load() method.
    }

    public function supports(mixed $resource, string $type = null)
    {
        // TODO: Implement supports() method.
    }

    public function getRoutesFor(string $name): RouteCollection
    {
        // TODO: Implement getRoutesFor() method.
    }
}