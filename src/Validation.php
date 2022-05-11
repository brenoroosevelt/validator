<?php

declare(strict_types=1);

namespace App;

interface Validation
{
    public function isValid(mixed $input, array $context = []): ValidationResult;
    public function setField(string $field): void;
    public function getField(): ?string;
}
