<?php

declare(strict_types=1);

namespace App\Infra\Quote\ApiClient;

use App\Domain\Source\Api\Client\RestApiClient;

interface RestClient extends RestApiClient, ClientInterface
{
    public function getServerTime(): int;
}
