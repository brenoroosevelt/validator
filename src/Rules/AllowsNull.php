<?php

declare(strict_types=1);

namespace App\Rules;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class AllowsNull implements Validation
{
    use Fieldable;

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        return ValidationResult::valid();
    }
}
