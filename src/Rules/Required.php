<?php

declare(strict_types=1);

namespace App\Rules;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Required implements Validation
{
    use Fieldable;

    public function __construct(private readonly string $message)
    {
    }

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        return
            null !== $this->field && !array_key_exists($this->field, $context) ?
                ValidationResult::invalid($this->message) :
                ValidationResult::valid();
    }
}
