<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\AllowsNull;
use App\Validation;
use PHPUnit\Framework\TestCase;

class NullableTest extends TestCase
{
    public function testNullableIsOk(): void
    {
        $rule = new AllowsNull();
        $this->assertTrue($rule->isValid([])->isOk());
        $this->assertInstanceOf(Validation::class, $rule);
    }
}
