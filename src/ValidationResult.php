<?php

declare(strict_types=1);

namespace App;

class ValidationResult
{
    private function __construct(public readonly ?string $message)
    {
    }

    public static function valid(): self
    {
        return new self(null);
    }

    public static function invalid(string $message): self
    {
        return new self($message);
    }

    public function isOk(): bool
    {
        return null === $this->message;
    }
}
