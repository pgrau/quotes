<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\UI\Controller\GetQuotesByAuthor;

use Quote\Api\Application\Query\GetShoutsByAuthor\GetShoutsByAuthorQuery;
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

            $query = new GetShoutsByAuthorQuery($authorId, (int) $limit);

            return new JsonResponse($this->queryBus->ask($query)->response());

        } catch (BadRequestException|NotFoundException|ConflictException $e) {

            return new JsonResponse(ApiError::create($e::CODE_STRING, $e->getMessage())->toArray(), $e::CODE_HTTP);

        } catch (\Exception $e) {

            return new JsonResponse(ApiError::create('TECHNICAL_ERROR', 'Unexpected API error', 500));
        }
    }
}
