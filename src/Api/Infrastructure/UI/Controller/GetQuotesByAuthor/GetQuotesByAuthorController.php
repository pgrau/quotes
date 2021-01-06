<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\UI\Controller\GetQuotesByAuthor;

use Quote\Api\Application\Query\GetQuotesByAuthor\GetQuotesByAuthorQuery;
use Quote\Shared\Domain\Model\Api\ApiError;
use Quote\Shared\Domain\Model\Bus\QueryBus;
use Quote\Shared\Domain\Model\Exception\BadRequestException;
use Quote\Shared\Domain\Model\Exception\ConflictException;
use Quote\Shared\Domain\Model\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetQuotesByAuthorController
{
    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $limit = $request->query->get('limit') ?? 10;
            $authorId = $request->get('author_id');

            $query = new GetQuotesByAuthorQuery($authorId, (int) $limit);

            $response = $this->queryBus->ask($query);

            return new JsonResponse($response->toArray());

        } catch (BadRequestException|NotFoundException|ConflictException $e) {

            return new JsonResponse(ApiError::create($e::CODE_STRING, $e->getMessage())->toArray(), $e::CODE_HTTP);

        } catch (\Exception $e) {

            return new JsonResponse(ApiError::create('TECHNICAL_ERROR', 'UNAVAILABLE API')->toArray(), 500);
        }
    }
}
