<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetQuotesByAuthor;

use Quote\Api\Domain\Model\Quote\Quote;

final class GetQuotesByAuthorQueryResponse
{
    public function __construct(private array $collection)
    {
    }

    public function toArray(): array
    {
        $response = [];
        /** @var $quote Quote */
        foreach ($this->collection as $quote) {
            $response[] = strtoupper($quote->quote());
        }

        return $response;
    }
}