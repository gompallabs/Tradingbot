<?php

declare(strict_types=1);

namespace App\Domain\Source\Api\Client;

use App\Domain\Source\Source;
use Symfony\Component\Routing\RouteCollection;

interface RouteLoader
{
    public function getRoutesForSource(Source $source): RouteCollection;

    public function supports(Source $source, string $type = null): bool;
}
