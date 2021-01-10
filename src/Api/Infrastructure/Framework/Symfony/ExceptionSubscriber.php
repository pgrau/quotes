<?php

declare(strict_types=1);

namespace Quote\Api\Infrastructure\Framework\Symfony;

use Quote\Shared\Domain\Model\Api\ApiError;
use Quote\Shared\Domain\Model\Exception\ProjectException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof ProjectException) {
            $content = ApiError::create($e::CODE_STRING, $e->getMessage())->toArray();
            $code = $e::CODE_HTTP;
        } else {
            $content = ApiError::create('TECHNICAL_ERROR', 'Unexpected API error')->toArray();
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $event->setResponse(new JsonResponse($content, $code));

        return;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
