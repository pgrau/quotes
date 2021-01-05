<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Author;

interface AuthorRepository
{
    public function findOne(string $id): ?Author;

    public function getOne(string $id): Author;
}
