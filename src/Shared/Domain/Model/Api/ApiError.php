<?php

declare(strict_types=1);

namespace Quote\Shared\Domain\Model\Api;

class ApiError
{
    public function __construct(private string $code, private string $message)
    {
    }

    public static function create(string $code, string $message): self
    {
        return new self($code, $message);
    }

    public function toArray(): array
    {
        return [
            'error' => [
                'code' => $this->code,
                'message' => $this->message
            ]
        ];
    }
}
