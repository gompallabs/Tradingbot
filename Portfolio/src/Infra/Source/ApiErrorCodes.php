<?php

declare(strict_types=1);

namespace App\Infra\Source;

use App\Infra\Storage\Storage;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ApiErrorCodes
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column('integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Storage::class)]
    #[ORM\JoinColumn(name: 'storage_id', referencedColumnName: 'id')]
    private Storage $storage;

    public function __construct(
        #[ORM\Column(type: 'integer')]
        private readonly int $code,

        #[ORM\Column(type: 'text')]
        private readonly string $message,

        #[ORM\Column(type: 'integer', nullable: true)]
        private ?int $httpStatusCode = null
    ) {
    }

    public function setHttpStatusCode(?int $httpStatusCode): void
    {
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStorage(): Storage
    {
        return $this->storage;
    }

    public function setStorage(Storage $storage): void
    {
        $this->storage = $storage;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }
}
