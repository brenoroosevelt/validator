<?php

declare(strict_types=1);

namespace Tests;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class DummyValidation implements Validation
{
    use Fieldable;

    public function __construct(
        private readonly bool $result,
        private readonly string $message
    ) {
    }

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        return $this->result ? ValidationResult::valid() : ValidationResult::invalid($this->message);
    }
}
