<?php

namespace App\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FixturesContext implements Context
{
    /**
     * @Given I load the fixtures
     */
    public function iLoadTheFixtures()
    {
        $this->clearSchema();
        $process = Process::fromShellCommandline('APP_ENV=test bin/console doctrine:fixtures:load -n');
        $process->run();
        echo $process->getOutput();
    }

    /**
     * @Given I load the fixtures group :arg1
     */
    public function iLoadTheFixturesGroup($arg1)
    {
        $this->clearSchema();
        $process = Process::fromShellCommandline(
            sprintf('APP_ENV=test bin/console doctrine:fixtures:load --group=%s -n', $arg1)
        );
        $process->run();
    }

    private function clearSchema()
    {
        $process = Process::fromShellCommandline('APP_ENV=test bin/console doctrine:database:drop --force');
        $process->run();
        $process = Process::fromShellCommandline('APP_ENV=test bin/console doctrine:database:create');
        $process->run();
        $process = Process::fromShellCommandline('APP_ENV=test bin/console doctrine:migrations:migrate -n');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }
}
