<?php

declare(strict_types=1);

namespace App;

use JsonSerializable;

final class Error implements JsonSerializable
{
    public function __construct(
        public readonly string $error,
        public readonly ?string $field = null
    ) {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
