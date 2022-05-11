<?php

declare(strict_types=1);

namespace Tests\Rules;

use App\Rules\Boolean;
use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase
{
    public function validDataProvider(): array
    {
        return [
            [true],
            [1],
            [false],
            [0],
        ];
    }

    public function invalidDataProvider(): array
    {
        return [
            [''],
            ['0'],
            ['1'],
            [null],
            ['false'],
            ['true'],
            [[]],
        ];
    }

    /** @dataProvider validDataProvider */
    public function testValidBoolean(mixed $input): void
    {
        $rule = new Boolean('invalid boolean');
        $result = $rule->isValid($input);
        $this->assertTrue($result->isOk());
    }

    /** @dataProvider invalidDataProvider */
    public function testInvalidBoolean(mixed $input): void
    {
        $rule = new Boolean('invalid boolean');
        $result = $rule->isValid($input);
        $this->assertFalse($result->isOk());
        $this->assertEquals('invalid boolean', $result->message);
    }
}
