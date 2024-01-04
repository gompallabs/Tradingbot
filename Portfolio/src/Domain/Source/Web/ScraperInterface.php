<?php

namespace App\Domain\Source\Web;

interface ScraperInterface
{
    public function supports(string $name): bool;
}
