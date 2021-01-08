<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Quote;

use Quote\Api\Domain\Model\Author\Author;
use Quote\Shared\Domain\Model\Aggregate\AggregateRoot;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Quote extends AggregateRoot
{
    public function __construct(private UuidInterface $id, private Author $author, private string $quote)
    {
    }

    public static function create(Author $author, string $quote): self
    {
        $id = Uuid::uuid4();
        $aggregate = new self($id, $author, trim($quote));

        $aggregate->record(
            new QuoteCreatedV1($aggregate->id()->toString(), $aggregate->quote(), $aggregate->author()->id())
        );

        return $aggregate;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function author(): Author
    {
        return $this->author;
    }

    public function quote(): string
    {
        return $this->quote;
    }

    public function toString(): string
    {
        return $this->quote();
    }
}