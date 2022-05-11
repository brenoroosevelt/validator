<?php

declare(strict_types=1);

namespace App\Rules;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Integer implements Validation
{
    use Fieldable;

    public function __construct(private readonly string $message)
    {
    }

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        return $this->isInteger($input) ? ValidationResult::valid() : ValidationResult::invalid($this->message);
    }

    private function isInteger(mixed $input): bool
    {
        if (!is_numeric($input)) {
            return false;
        }

        $diff = (float) $input - (int) $input;
        return $diff == 0;
    }
}
