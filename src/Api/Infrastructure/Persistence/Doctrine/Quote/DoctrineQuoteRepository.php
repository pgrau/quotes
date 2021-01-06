<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\Persistence\Doctrine\Quote;

use Doctrine\ORM\EntityRepository;
use Quote\Api\Domain\Model\Quote\QuoteRepository;

class DoctrineQuoteRepository extends EntityRepository implements QuoteRepository
{
    public function findByAuthor(string $authorId, int $limit = 10): array
    {
        return $this->findBy(['author' => $authorId], null, $limit);
    }
}
