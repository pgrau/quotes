<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Author;

use Quote\Shared\Domain\Model\Exception\NotFoundException;

class AuthorNotFound extends NotFoundException
{
    public const CODE_STRING = 'AUTHOR_NOT_FOUND';

    public static function byId(string $id): self
    {
        $msg = \sprintf('Author %d not found', $id);

        return new self($msg);
    }
}