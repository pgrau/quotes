<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetQuotesByAuthor;
use Quote\Shared\Domain\Model\Query;

final class GetQuotesByAuthorQuery implements Query
{
    public function __construct(private string $authorId, private int $limit)
    {
    }

    public function authorId(): string
    {
        return $this->authorId;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
