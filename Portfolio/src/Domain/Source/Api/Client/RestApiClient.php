<?php

declare(strict_types=1);

namespace App\Domain\Source\Api\Client;

interface RestApiClient extends ApiClient
{
    public function hasCredentials(): bool;

    public function setApiKey(?string $apiKey): void;

    public function setApiKeySecret(?string $apiKey): void;
}