<?php

declare(strict_types=1);

namespace App\Infra\Source;

use App\Domain\Source\Api\SourceApiType;
use App\Domain\Source\Source;

final class BitgetApiSource implements Source
{
    public const NAME = 'bitget';

    private ?SourceApiType $sourceApiType = null;

    public function __construct(SourceApiType $apiType)
    {
        $this->sourceApiType = $apiType;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getSourceApiType(): SourceApiType
    {
        return $this->sourceApiType;
    }

    public static function ofType(string $type): self
    {
        return new self(SourceApiType::tryFrom($type));
    }
}
