<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Exception;

class BadRequestException extends ProjectException
{
    public const CODE_HTTP = 400;
    public const CODE_STRING = 'BAD_REQUEST';
}
