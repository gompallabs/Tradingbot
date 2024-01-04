<?php

declare(strict_types=1);

namespace App\Infra\Source\ApiClient\ClientBuilder\Routes;

use App\Domain\Source\Api\Client\LoaderRegistry;

abstract class RouteLoaderRegistry implements LoaderRegistry
{
    private \IteratorAggregate $loaders;

    public function __construct(\IteratorAggregate $loaders)
    {
        $this->loaders = $loaders;
    }

    public function load(mixed $resource, string $type = null)
    {
        foreach ($this->loaders->getIterator() as $loader){
            if($loader->supports($resource)){
                return $loader->load();
            };
        }
    }

    public function supports(mixed $resource, string $type = null)
    {
        foreach ($this->loaders as $loader){
            if($loader->supports($resource)){
                return true;
            }
        }
        return false;
    }
}