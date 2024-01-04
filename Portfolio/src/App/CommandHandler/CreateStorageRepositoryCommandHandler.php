<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateStorageRepositoryCommand;
use App\Domain\Storage\Orm\Doctrine\StorageRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateStorageRepositoryCommandHandler
{
    private StorageRepositoryInterface $assetRepository;

    public function __construct(StorageRepositoryInterface $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    public function __invoke(CreateStorageRepositoryCommand $command): void
    {
        $exist = $this->assetRepository->findOneBy([
            'name' => trim(strtolower($command->getStorageRepository()->getName())),
        ]);

        if ($exist === null) {
            $this->assetRepository->save($command->getStorageRepository());

            return;
        }
        throw new \RuntimeException('Custodian already exists.');
    }
}
