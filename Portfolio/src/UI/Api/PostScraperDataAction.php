<?php

declare(strict_types=1);

namespace App\UI\Api;

use App\App\Command\CreateRestErrorCodesCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
#[Route(path: '/scraper', name: 'gather_js_scraper_data', methods: ['GET', 'POST'])]
final class PostScraperDataAction
{
    private MessageBusInterface $commandBus;

    public function __construct(
        LoggerInterface $logger,
        MessageBusInterface $commandBus,
    ) {
        $this->logger = $logger;
        $this->commandBus = $commandBus;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $repository = $payload['repository'];
        $page = $payload['page'];
        $data = json_decode($payload['data'], true);

        $command = match ($page) {
            'restapi-error-codes' => CreateRestErrorCodesCommand::class,
            default => null
        };

        if ($command !== null) {
            $command = new $command($repository, $data);
            $this->commandBus->dispatch($command);
        }

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
