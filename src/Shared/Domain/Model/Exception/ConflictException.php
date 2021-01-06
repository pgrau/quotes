<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Exception;

abstract class ConflictException extends ProjectException
{
    public const CODE_HTTP = 409;
}