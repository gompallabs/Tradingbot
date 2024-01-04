<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Process;

class DbContext implements Context
{
    public const DB_INIT = [
        'doctrine:database:drop --force',
        'doctrine:database:create -n',
        'doctrine:schema:update --force',
    ];

    /**
     * @Given the database schema is updated
     */
    public function theDatabaseSchemaIsUpdated()
    {
        $consolePath = 'bin/console';
        foreach (self::DB_INIT as $command) {
            $process = Process::fromShellCommandline(sprintf('%s %s', $consolePath, $command));
            $process->run();
            if (!$process->isSuccessful()) {
                throw new \RuntimeException('Doctrine migrations failed: '.$process->getErrorOutput());
            }
        }
    }
}
