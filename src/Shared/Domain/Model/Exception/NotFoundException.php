<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Exception;

abstract class NotFoundException extends \RuntimeException
{
    public const CODE_STRING = '';
}