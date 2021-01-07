<?php

namespace Quote\Api\Tests\Behat;

use Behat\Behat\Context\Context;

final class SymfonyContext implements Context
{
    public function __construct(private string $environment)
    {
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
