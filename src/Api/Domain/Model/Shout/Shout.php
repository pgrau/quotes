<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Shout;

use Quote\Api\Domain\Model\Quote\Quote;

class Shout
{
    public function __construct(private string $shout)
    {
        $this->applyRules();
    }

    public static function fromQuote(Quote $quote): self
    {
        return new self($quote->toString());
    }

    public function toString(): string
    {
        return $this->shout;
    }

    private function applyRules(): void
    {
        $this->shout = strtoupper(trim($this->shout));

        if ($this->shout[strlen($this->shout) - 1] === '.') {
            $this->shout = substr($this->shout,0,strlen($this->shout) - 1);
        }

        if ($this->shout[strlen($this->shout) - 1] !== '!') {
            $this->shout .= '!';
        }
    }
}
