<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\UI\Controller\Ping;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PingController
{
    public function __invoke(Request $request): Response
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
