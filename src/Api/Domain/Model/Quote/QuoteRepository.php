<?php

declare(strict_types=1);

namespace Quote\Api\Domain\Model\Quote;

interface QuoteRepository
{
    /**
     * @return Quote[]
     */
    public function findByAuthor(string $authorId, int $limit = 10): array;
}
