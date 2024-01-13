<?php

declare(strict_types=1);

namespace App\Domain\Source\Api\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface RestApiClient extends ApiClient, HttpClientInterface
{
    public function hasCredentials(): bool;

    public function setApiKey(?string $apiKey): void;

    public function setApiKeySecret(?string $apiKeySecret): void;

    public function setApiKeyPassphrase(?string $apiKeyPassphrase): void;

    public function getServerTime(): int;

    public function accountBalance(array $options);
}
