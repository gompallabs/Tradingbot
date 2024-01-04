<?php

declare(strict_types=1);

namespace App\Domain\Source\Api\Client;

use Symfony\Component\Routing\RouteCollection;

interface LoaderRegistry
{
    public function getRoutesFor(string $name): RouteCollection;
}