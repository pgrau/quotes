<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Exception;

abstract class ProjectException extends \RuntimeException
{
    public const CODE_HTTP = '';
    public const CODE_STRING = '';
}
