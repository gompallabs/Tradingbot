<?php

namespace App\Infra\Source\Source;

use App\Domain\Source\Api\SourceApiType;
use App\Domain\Source\Source;

class JupiterApiSource implements Source
{
    public const NAME = 'jupiter';

    private ?SourceApiType $sourceApiType = null;

    public function __construct(SourceApiType $apiType)
    {
        $this->sourceApiType = $apiType;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public static function ofType(string $type): self
    {
        return new self(SourceApiType::tryFrom(
            trim(ucfirst($type))
        ));
    }
}