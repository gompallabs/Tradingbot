<?php

declare(strict_types=1);

namespace App\App\Command;

final class CreateRestErrorCodesCommand
{
    private string $repository;
    private array $data;

    public function __construct(string $repository, array $data)
    {
        $this->repository = $repository;
        $this->data = $data;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
