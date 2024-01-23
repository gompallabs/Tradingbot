<?php

declare(strict_types=1);

namespace App\UI\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WebSocket\Client as Client;
use WebSocket\Middleware\CloseHandler;
use WebSocket\Middleware\PingResponder;

#[AsCommand(name: 'app:ws-connect:binance')]
class BinanceConnectCommand extends Command
{
    public function __construct(string $name = null)
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $count = 1000;
        $client = new Client("wss://fstream.binance.com/ws/btcusdt@aggTrade");
        $client
            // Add standard middlewares
            ->addMiddleware(new CloseHandler())
            ->addMiddleware(new PingResponder())
        ;
        while($count>0){
            $message = $client->receive();
            echo "{$message->getContent()} \n";
            $count--;
        }

        return 0;
    }
}