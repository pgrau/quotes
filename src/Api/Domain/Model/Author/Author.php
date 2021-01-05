<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Author;

use Quote\Shared\Domain\Model\Aggregate\AggregateRoot;

class Author extends AggregateRoot
{
    public function __construct(private string $id, private string $fullName)
    {
    }

    public static function create(string $fullName): self
    {
        $author = new self(self::getIdFromFullName($fullName), trim($fullName));

        $author->record(new AuthorCreatedV1($author->id(), $author->fullName()));

        return $author;
    }

    public static function getIdFromFullName(string $fullName): string
    {
        return strtolower(str_replace([' ', '.'], ['-', ''], trim($fullName)));
    }

    public function id(): string
    {
        return $this->id;
    }

    public function fullName(): string
    {
        return $this->fullName;
    }
}
