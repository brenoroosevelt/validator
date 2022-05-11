<?php

declare(strict_types=1);

namespace Tests\Initializer;

use App\ObjectInitializer;
use App\Rules\AllowsNull;
use App\Rules\DateTime;
use App\Rules\Integer;
use App\Rules\IsString;
use DateTimeImmutable;

class Class1
{
    use ObjectInitializer;

    public function __construct(
        #[IsString('invalid string')]
        public readonly string $name,

        #[Integer('invalid integer')]
        public readonly int $value,

        #[AllowsNull]
        #[DateTime('invalid datetime')]
        public readonly ?DateTimeImmutable $date
    ) {
    }
}
