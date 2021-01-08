<?php

declare(strict_types=1);

namespace Quote\Api\Application\Query\GetShoutsByAuthor;
use Quote\Shared\Domain\Model\Query;

final class GetShoutsByAuthorQuery implements Query
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
