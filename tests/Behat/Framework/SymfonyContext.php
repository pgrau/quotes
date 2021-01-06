<?php

namespace Quote\Api\Tests\Behat\Framework;

use Behat\Behat\Context\Context;

final class SymfonyContext implements Context
{
    /** @var string */
    private $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @Then the application's kernel should use :expected environment
     */
    public function kernelEnvironmentShouldBe(string $expected): void
    {
        if ($this->environment !== $expected) {
            throw new \RuntimeException('Environment must be test');
        }
    }
}
