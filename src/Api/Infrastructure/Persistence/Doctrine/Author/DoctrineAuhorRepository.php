<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\Persistence\Doctrine\Author;

use Doctrine\ORM\EntityRepository;
use Quote\Api\Domain\Model\Author\Author;
use Quote\Api\Domain\Model\Author\AuthorNotFound;
use Quote\Api\Domain\Model\Author\AuthorRepository;

class DoctrineAuhorRepository extends EntityRepository implements AuthorRepository
{
    public function findOne(string $id): ?Author
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getOne(string $id): Author
    {
        if (! $obj = $this->findOne($id)) {
            throw AuthorNotFound::byId($id);
        }

        return $obj;
    }
}
