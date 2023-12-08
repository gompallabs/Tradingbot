<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class InitContext implements Context
{
    private ?float $startTime = null;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Given I measure time in milliseconds
     */
    public function iMeasureTimeInMilliseconds()
    {
        $this->startTime = floor(microtime(true)*1000);
        echo sprintf("start-time: %s", $this->startTime);
        assert(strlen((string)$this->startTime) === 14);
    }

    /**
     * @Given I request the rest endpoint :arg1 of :arg2 to check if my connexion is alive and my computer is sync
     */
    public function iRequestTheRestEndpointOfToCheckIfMyConnexionIsAliveAndMyComputerIsSync($arg1, $arg2)
    {
        try {
            $route = $this->urlGenerator->generate(sprintf("%s_server_time", $arg2));
        } catch (RouteNotFoundException $exception) {
            throw new \Exception(sprintf("Route not found. %s", $exception->getMessage()));
        }



    }
}