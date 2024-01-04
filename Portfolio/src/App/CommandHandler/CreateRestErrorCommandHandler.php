<?php

declare(strict_types=1);

namespace App\App\CommandHandler;

use App\App\Command\CreateRestErrorCodesCommand;
use App\Domain\Source\Api\Orm\Doctrine\ApiErrorCodesRepositoryInterface;
use App\Infra\Source\ApiErrorCodes;
use App\Infra\Storage\CryptoExchange;
use App\Infra\Storage\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateRestErrorCommandHandler
{
    private ApiErrorCodesRepositoryInterface $errorCodesRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateRestErrorCodesCommand $command): void
    {
        $em = $this->entityManager;
        $repo = $em->getRepository(CryptoExchange::class)->findOneBy([
            'name' => trim(strtolower($command->getRepository())),
        ]);

        if ($repo instanceof Storage) {
            foreach ($command->getData() as $errorCode) {
                if (count($errorCode) > 1) {
                    array_map(function (array $row) use ($repo, $em) {
                        if (count($row) > 1) {
                            $code = new ApiErrorCodes(
                                code: (int) $row[0],
                                message: $row[1],
                            );
                            if (array_key_exists(2, $row)) {
                                $code->setHttpStatusCode((int) $row[2]);
                            }
                            $code->setRepository($repo);

                            $existingCode = $this->entityManager->getRepository(ApiErrorCodes::class)->findOneBy([
                                'code' => $code->getCode(),
                                'repository' => $repo->getId(),
                            ]);
                            if ($existingCode === null) {
                                $em->persist($code);
                            }
                        }
                    }, $errorCode);
                }
            }
            $em->flush();
        }
    }
}
