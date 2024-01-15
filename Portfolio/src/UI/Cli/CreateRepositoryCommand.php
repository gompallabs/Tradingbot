<?php

declare(strict_types=1);

namespace App\UI\Cli;

use App\App\Command\CreateStorageRepositoryCommand as CreateCommand;
use App\Infra\Storage\Broker;
use App\Infra\Storage\SovereignWallet;
use App\Infra\Storage\CryptoExchange;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:create-asset-repo',
    description: 'Create an asset repository, broker, crypto exchange, cold wallet',
    aliases: ['app:create-asset-repo']
)]
final class CreateRepositoryCommand extends Command
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question("Which AssetInterface Type is in custody ?
        \n[1] Broker account
        \n[2] Crypto exchange
        \n[3] Cold Wallet\n\n");
        $userInput = $helper->ask($input, $output, $question);
        $custodyType = (int) $userInput;

        $question = new Question("Enter the Name\n");
        $userInput = $helper->ask($input, $output, $question);
        $custodyName = trim(strtolower($userInput));

        $newCustody = match ($custodyType) {
            1 => new Broker(name: $custodyName),
            2 => new CryptoExchange(name: $custodyName),
            3 => new SovereignWallet(name: $custodyName)
        };

        $this->commandBus->dispatch(new CreateCommand($newCustody));

        return Command::SUCCESS;
    }
}
