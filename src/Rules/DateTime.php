<?php

declare(strict_types=1);

namespace App\Rules;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;
use DateTimeImmutable;
use DateTimeInterface;
use Throwable;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DateTime implements Validation
{
    use Fieldable;

    public function __construct(private readonly string $message)
    {
    }

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        if ($input instanceof DateTimeInterface) {
            return ValidationResult::valid();
        }

        try {
            new DateTimeImmutable($input);
            return ValidationResult::valid();
        } catch (Throwable) {
            return ValidationResult::invalid($this->message);
        }
    }
}
