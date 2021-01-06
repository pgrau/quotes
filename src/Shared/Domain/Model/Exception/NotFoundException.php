<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Exception;

abstract class NotFoundException extends ProjectException
{
    public const CODE_HTTP = 404;
}