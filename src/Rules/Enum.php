<?php

declare(strict_types=1);

namespace App\Rules;

use App\Fieldable;
use App\Validation;
use App\ValidationResult;
use Attribute;
use RuntimeException;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Enum implements Validation
{
    use Fieldable;

    public function __construct(
        private string $enumType,
        private readonly string $message,
    ) {
        if (!is_a($this->enumType, \BackedEnum::class, true)) {
            throw new RuntimeException('Invalid enum type: ' . $this->enumType);
        }
    }

    public function isValid(mixed $input, array $context = []): ValidationResult
    {
        return
            null !== call_user_func_array([sprintf('%s::%s', $this->enumType, 'tryFrom')], [$input]) ?
                ValidationResult::valid() :
                ValidationResult::invalid($this->message);
    }
}
