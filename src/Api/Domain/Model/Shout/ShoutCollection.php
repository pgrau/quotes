<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Shout;

use Quote\Api\Domain\Model\Quote\Quote;

class ShoutCollection
{
    public function __construct(private array $collection)
    {
    }

    public static function fromQuotes(Quote... $quotes): self
    {
        foreach ($quotes as $quote) {
            $collection[] = Shout::fromQuote($quote);
        }

        return new self($collection);
    }

    public function get(): array
    {
        return $this->collection;
    }
}
