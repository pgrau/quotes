<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\UI\Controller\GetShoutsByAuthor;

use Quote\Api\Application\Query\GetShoutsByAuthor\GetShoutsByAuthorQuery;
use Quote\Shared\Domain\Model\Bus\QueryBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetShoutsByAuthorController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $limit = $request->query->get('limit') ?? 10;
        $authorId = $request->get('author_id');

        $query = new GetShoutsByAuthorQuery($authorId, (int) $limit);

        return new JsonResponse($this->queryBus->ask($query)->response());
    }
}
